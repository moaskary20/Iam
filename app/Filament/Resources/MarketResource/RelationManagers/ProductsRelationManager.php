<?php

namespace App\Filament\Resources\MarketResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';
    
    protected static ?string $title = 'المنتجات';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم المنتج')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('الوصف')
                    ->required()
                    ->rows(4),
                Forms\Components\FileUpload::make('images')
                    ->label('صور المنتج (3 صور)')
                    ->image()
                    ->disk('public')
                    ->directory('products')
                    ->multiple()
                    ->minFiles(1)
                    ->maxFiles(3)
                    ->required(),
                Forms\Components\TextInput::make('purchase_price')
                    ->label('سعر الشراء')
                    ->numeric()
                    ->prefix('$')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                        if ($state) {
                            $expectedPrice = floatval($state) * 1.15; // زيادة 15%
                            $set('expected_selling_price', number_format($expectedPrice, 2, '.', ''));
                        }
                    }),
                Forms\Components\TextInput::make('expected_selling_price')
                    ->label('سعر البيع المتوقع')
                    ->numeric()
                    ->prefix('$')
                    ->required()
                    ->readOnly(),
                Forms\Components\TextInput::make('system_commission')
                    ->label('عمولة النظام (%)')
                    ->numeric()
                    ->suffix('%')
                    ->default(5.00)
                    ->required(),
                Forms\Components\TextInput::make('marketing_commission')
                    ->label('عمولة التسويق والبيع التلقائي (%)')
                    ->numeric()
                    ->suffix('%')
                    ->default(3.00)
                    ->required(),
                Forms\Components\TextInput::make('order')
                    ->label('الترتيب')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('active')
                    ->label('نشط')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المنتج')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('images')
                    ->label('الصور')
                    ->disk('public')
                    ->stacked()
                    ->limit(3),
                Tables\Columns\TextColumn::make('purchase_price')
                    ->label('سعر الشراء')
                    ->sortable()
                    ->money('USD'),
                Tables\Columns\TextColumn::make('expected_selling_price')
                    ->label('سعر البيع المتوقع')
                    ->money('USD'),
                Tables\Columns\TextColumn::make('system_commission')
                    ->label('عمولة النظام')
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('marketing_commission')
                    ->label('عمولة التسويق')
                    ->suffix('%'),
                Tables\Columns\IconColumn::make('active')
                    ->label('نشط')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('الحالة'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('إضافة منتج'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order');
    }
}
