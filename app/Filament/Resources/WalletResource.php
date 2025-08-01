<?php

namespace App\Filament\Resources;

use App\Models\Wallet;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\WalletResource\Pages;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'المحافظ';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('المستخدم')
                ->options(User::all()->pluck('first_name', 'id')),
            Forms\Components\TextInput::make('balance')
                ->label('الرصيد')
                ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.first_name')->label('اسم المستخدم'),
                Tables\Columns\TextColumn::make('balance')->label('الرصيد'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('deposit')
                    ->label('إيداع رصيد')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('amount')
                            ->label('المبلغ')
                            ->numeric()
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $amount = $data['amount'];
                        $feeType = config('wallet.deposit_fee_type', 'fixed');
                        $feeValue = config('wallet.deposit_fee', 0);
                        $fee = $feeType === 'percent' ? ($amount * $feeValue / 100) : $feeValue;
                        $record->balance += ($amount - $fee);
                        $record->save();
                        \App\Models\WalletTransaction::create([
                            'wallet_id' => $record->id,
                            'type' => 'deposit',
                            'amount' => $amount,
                            'fee' => $fee,
                            'description' => 'إيداع رصيد',
                        ]);
                    }),
                Tables\Actions\Action::make('withdraw')
                    ->label('سحب رصيد')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('amount')
                            ->label('المبلغ')
                            ->numeric()
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $amount = $data['amount'];
                        $feeType = config('wallet.withdraw_fee_type', 'fixed');
                        $feeValue = config('wallet.withdraw_fee', 0);
                        $fee = $feeType === 'percent' ? ($amount * $feeValue / 100) : $feeValue;
                        $total = $amount + $fee;
                        if ($record->balance < $total) {
                            throw new \Exception('الرصيد غير كاف');
                        }
                        $record->balance -= $total;
                        $record->save();
                        \App\Models\WalletTransaction::create([
                            'wallet_id' => $record->id,
                            'type' => 'withdraw',
                            'amount' => $amount,
                            'fee' => $fee,
                            'description' => 'سحب رصيد',
                        ]);
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWallets::route('/'),
            'edit' => Pages\EditWallet::route('/{record}/edit'),
            'deposit' => Pages\DepositWallet::route('/{record}/deposit'),
            'withdraw' => Pages\WithdrawWallet::route('/{record}/withdraw'),
        ];
    }
}
