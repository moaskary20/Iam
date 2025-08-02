<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;
use Filament\Tables\Filters\SelectFilter;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationLabel = 'الطلبات';
    
    protected static ?string $modelLabel = 'طلب';
    
    protected static ?string $pluralModelLabel = 'الطلبات';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الطلب')
                    ->schema([
                        TextInput::make('order_number')
                            ->label('رقم الطلب')
                            ->disabled()
                            ->default(fn () => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6))),
                            
                        Select::make('user_id')
                            ->label('المستخدم')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                            
                        Select::make('product_id')
                            ->label('المنتج')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                            
                        Select::make('sell_method')
                            ->label('طريقة البيع')
                            ->options([
                                'shipping' => '🚚 شحن منزلي',
                                'ai' => '🤖 ذكاء اصطناعي',
                                'social' => '📱 سوشيال ميديا',
                            ])
                            ->required(),
                            
                        Select::make('status')
                            ->label('حالة الطلب')
                            ->options([
                                'pending' => '⏳ في الانتظار',
                                'processing' => '🔄 قيد المعالجة',
                                'shipped' => '🚚 تم الشحن',
                                'delivered' => '✅ تم التسليم',
                                'cancelled' => '❌ ملغي',
                            ])
                            ->default('pending')
                            ->required(),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('التفاصيل المالية')
                    ->schema([
                        TextInput::make('product_price')
                            ->label('سعر المنتج')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                            
                        TextInput::make('marketing_fee')
                            ->label('رسوم التسويق')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                            
                        TextInput::make('system_commission')
                            ->label('عمولة النظام')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                            
                        TextInput::make('total_cost')
                            ->label('التكلفة الإجمالية')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('تفاصيل إضافية')
                    ->schema([
                        KeyValue::make('shipping_details')
                            ->label('تفاصيل الشحن')
                            ->keyLabel('البيان')
                            ->valueLabel('القيمة'),
                            
                        TextInput::make('share_link')
                            ->label('رابط المشاركة')
                            ->url(),
                            
                        TextInput::make('referrer_id')
                            ->label('معرف المحيل'),
                            
                        Textarea::make('notes')
                            ->label('ملاحظات')
                            ->rows(3),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('رقم الطلب')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                    
                TextColumn::make('user.name')
                    ->label('المستخدم')
                    ->searchable()
                    ->sortable()
                    ->default('مستخدم تجريبي'),
                    
                TextColumn::make('product.name')
                    ->label('المنتج')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                    
                BadgeColumn::make('sell_method')
                    ->label('طريقة البيع')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'shipping' => 'شحن منزلي',
                        'ai' => 'ذكاء اصطناعي',
                        'social' => 'سوشيال ميديا',
                        default => $state,
                    })
                    ->colors([
                        'primary' => 'shipping',
                        'info' => 'ai',
                        'success' => 'social',
                    ])
                    ->icons([
                        'heroicon-o-truck' => 'shipping',
                        'heroicon-o-cpu-chip' => 'ai',
                        'heroicon-o-share' => 'social',
                    ]),
                    
                BadgeColumn::make('status')
                    ->label('الحالة')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'في الانتظار',
                        'processing' => 'قيد المعالجة',
                        'shipped' => 'تم الشحن',
                        'delivered' => 'تم التسليم',
                        'cancelled' => 'ملغي',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'primary' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ]),
                    
                TextColumn::make('total_cost')
                    ->label('التكلفة الإجمالية')
                    ->money('USD')
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending' => 'في الانتظار',
                        'processing' => 'قيد المعالجة',
                        'shipped' => 'تم الشحن',
                        'delivered' => 'تم التسليم',
                        'cancelled' => 'ملغي',
                    ]),
                    
                SelectFilter::make('sell_method')
                    ->label('طريقة البيع')
                    ->options([
                        'shipping' => 'شحن منزلي',
                        'ai' => 'ذكاء اصطناعي',
                        'social' => 'سوشيال ميديا',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض'),
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
