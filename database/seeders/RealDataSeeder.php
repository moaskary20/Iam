<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class RealDataSeeder extends Seeder
{
    public function run()
    {
        // Update first user with real data
        $user = User::first();
        if ($user) {
            // Update user balance
            DB::table('users')->where('id', $user->id)->update([
                'balance' => 3385.00
            ]);
            
            echo "Updated user {$user->name} balance to $3,385.00\n";
            
            // Get or create wallet
            $wallet = $user->wallet;
            if (!$wallet) {
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    'balance' => 3385.00
                ]);
            } else {
                $wallet->update(['balance' => 3385.00]);
            }
            
            // Clear existing transactions
            $wallet->transactions()->delete();
            
            // Add realistic transactions
            $transactions = [
                ['type' => 'deposit', 'amount' => 1500.00, 'description' => 'PayPal Deposit', 'status' => 'completed'],
                ['type' => 'deposit', 'amount' => 800.00, 'description' => 'Skrill Deposit', 'status' => 'completed'],
                ['type' => 'deposit', 'amount' => 1200.00, 'description' => 'Bank Transfer', 'status' => 'completed'],
                ['type' => 'deposit', 'amount' => 300.00, 'description' => 'Credit Card Deposit', 'status' => 'completed'],
                ['type' => 'withdraw', 'amount' => 150.00, 'description' => 'ATM Withdrawal', 'status' => 'completed'],
                ['type' => 'withdraw', 'amount' => 265.00, 'description' => 'Bank Transfer Out', 'status' => 'completed'],
            ];
            
            foreach ($transactions as $transaction) {
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => $transaction['type'],
                    'amount' => $transaction['amount'],
                    'description' => $transaction['description'],
                    'status' => $transaction['status'],
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()
                ]);
            }
            
            echo "Added " . count($transactions) . " transactions\n";
            
            // Calculate final statistics
            $totalDeposits = $wallet->transactions()->where('type', 'deposit')->sum('amount');
            $totalWithdrawals = $wallet->transactions()->where('type', 'withdraw')->sum('amount');
            $transactionCount = $wallet->transactions()->count();
            
            echo "Final Statistics:\n";
            echo "- Balance: $" . number_format(3385.00, 2) . "\n";
            echo "- Transactions: " . $transactionCount . "\n";
            echo "- Total Deposits: $" . number_format($totalDeposits, 2) . "\n";
            echo "- Total Withdrawals: $" . number_format($totalWithdrawals, 2) . "\n";
        }
    }
}
