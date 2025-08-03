<?php

namespace App\Filament\Resources;

use App\Models\PaymentMethod;
use App\Filament\Resources\PaymentMethodResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'طرق الدفع';
    protected static ?string $label = 'طريقة دفع';
    protected static ?string $pluralLabel = 'طرق الدفع';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('اسم الطريقة')
                ->required(),
            
            Forms\Components\Toggle::make('active')
                ->label('مفعلة')
                ->default(true),
            
            Forms\Components\Section::make('إعدادات باي بال')
                ->description('اتركها فارغة إذا لم تكن طريقة دفع باي بال')
                ->schema([
                    Forms\Components\TextInput::make('config.client_id')
                        ->label('PayPal Client ID')
                        ->nullable(),
                    
                    Forms\Components\TextInput::make('config.client_secret')
                        ->label('PayPal Client Secret')
                        ->password()
                        ->nullable(),
                    
                    Forms\Components\Select::make('config.mode')
                        ->label('البيئة')
                        ->options([
                            'sandbox' => 'تجريبي (Sandbox)',
                            'live' => 'حقيقي (Live)'
                        ])
                        ->default('sandbox'),
                    
                    Forms\Components\TextInput::make('config.currency')
                        ->label('العملة')
                        ->default('USD')
                        ->nullable(),
                ])
                ->collapsible()
                ->collapsed()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->label('اسم الطريقة'),
            Tables\Columns\IconColumn::make('active')->boolean()->label('مفعلة'),
        ])
        ->filters([
            // ...
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
