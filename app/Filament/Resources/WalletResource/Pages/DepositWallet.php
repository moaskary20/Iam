<?php

namespace App\Filament\Resources\WalletResource\Pages;

use App\Filament\Resources\WalletResource;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class DepositWallet extends Page
{
    protected static string $resource = WalletResource::class;
    public $record;
    public $amount;

    public function mount($record): void
    {
        $this->record = Wallet::findOrFail($record);
    }

    public function deposit()
    {
        $fee = config('wallet.deposit_fee');
        $amount = $this->amount;
        DB::transaction(function () use ($amount, $fee) {
            $this->record->balance += ($amount - $fee);
            $this->record->save();
            WalletTransaction::create([
                'wallet_id' => $this->record->id,
                'type' => 'deposit',
                'amount' => $amount,
                'fee' => $fee,
                'description' => 'إيداع رصيد',
            ]);
        });
        Notification::make()->title('تم الإيداع بنجاح')->success()->send();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('amount')
                ->label('المبلغ')
                ->numeric()
                ->required(),
        ];
    }

}
