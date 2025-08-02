<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Market;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    
    protected static ?string $navigationLabel = 'المنتجات';
    
    protected static ?string $modelLabel = 'منتج';
    
    protected static ?string $pluralModelLabel = 'المنتجات';
    
    protected static ?string $navigationGroup = 'إدارة المحتوى';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('market_id')
                    ->label('السوق')
                    ->options(Market::where('active', true)->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('market.name')
                    ->label('السوق')
                    ->badge()
                    ->searchable(),
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
                ->money('USD')
                ->sortable(),
            Tables\Columns\TextColumn::make('expected_selling_price')
                ->label('سعر البيع المتوقع')
                ->money('USD')
                ->sortable(),
                Tables\Columns\TextColumn::make('system_commission')
                    ->label('عمولة النظام')
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('marketing_commission')
                    ->label('عمولة التسويق')
                    ->suffix('%'),
                Tables\Columns\IconColumn::make('active')
                    ->label('نشط')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('market')
                    ->label('السوق')
                    ->relationship('market', 'name'),
                Tables\Filters\TernaryFilter::make('active')
                    ->label('الحالة'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
