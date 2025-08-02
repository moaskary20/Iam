<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class DemoUserAndWalletSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            // التحقق من وجود المستخدم أولاً
            $email = "user{$i}@example.com";
            $existingUser = User::where('email', $email)->first();
            
            if (!$existingUser) {
                $user = User::create([
                    'first_name' => 'User',
                    'last_name' => $i,
                    'name' => 'User ' . $i,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'phone' => '0100000000' . $i,
                    'country' => 'Egypt',
                    'verification_status' => 'approved',
                    'email_verified_at' => now(),
                    'is_verified' => true,
                    'current_market_id' => 1,
                    'current_product_index' => 0,
                    'unlocked_markets' => json_encode([1]),
                    'balance' => rand(100, 1000),
                ]);
                
                // إنشاء محفظة للمستخدم إذا لم تكن موجودة
                $existingWallet = Wallet::where('user_id', $user->id)->first();
                if (!$existingWallet) {
                    Wallet::create([
                        'user_id' => $user->id,
                        'balance' => $user->balance,
                        'currency' => 'USD',
                    ]);
                }
                
                $this->command->info("تم إنشاء المستخدم: {$user->email}");
            } else {
                $this->command->info("المستخدم موجود بالفعل: {$email}");
                
                // إنشاء محفظة إذا لم تكن موجودة
                $existingWallet = Wallet::where('user_id', $existingUser->id)->first();
                if (!$existingWallet) {
                    Wallet::create([
                        'user_id' => $existingUser->id,
                        'balance' => $existingUser->balance ?? rand(100, 1000),
                        'currency' => 'USD',
                    ]);
                    $this->command->info("تم إنشاء محفظة للمستخدم: {$email}");
                }
            }
        }
    }
}
