<?php

namespace App\Filament\Resources\WalletResource\Pages;

use App\Filament\Resources\WalletResource;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class WithdrawWallet extends Page
{
    protected static string $resource = WalletResource::class;
    public $record;
    public $amount;

    public function mount($record): void
    {
        $this->record = Wallet::findOrFail($record);
    }

    public function withdraw()
    {
        $fee = config('wallet.withdraw_fee');
        $amount = $this->amount;
        DB::transaction(function () use ($amount, $fee) {
            $total = $amount + $fee;
            if ($this->record->balance < $total) {
                Notification::make()->title('الرصيد غير كاف')->danger()->send();
                return;
            }
            $this->record->balance -= $total;
            $this->record->save();
            WalletTransaction::create([
                'wallet_id' => $this->record->id,
                'type' => 'withdraw',
                'amount' => $amount,
                'fee' => $fee,
                'description' => 'سحب رصيد',
            ]);
        });
        Notification::make()->title('تم السحب بنجاح')->success()->send();
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
