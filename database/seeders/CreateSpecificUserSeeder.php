<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateSpecificUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التحقق من وجود المستخدم أولاً
        $existingUser = User::where('email', 'mo.askary@gmail.com')->first();
        
        if ($existingUser) {
            // تحديث المستخدم إذا كان موجود
            $existingUser->update([
                'password' => Hash::make('newpassword'),
                'name' => 'محمد عسكري',
                'email_verified_at' => now(),
                'is_verified' => true,
                'verification_status' => 'verified',
                'current_market_id' => 1,
                'current_product_index' => 0,
                'unlocked_markets' => json_encode([1]),
                'balance' => 100.00,
            ]);
            
            $this->command->info('تم تحديث المستخدم: ' . $existingUser->email);
        } else {
            // إنشاء مستخدم جديد
            $user = User::create([
                'name' => 'محمد عسكري',
                'email' => 'mo.askary@gmail.com',
                'password' => Hash::make('newpassword'),
                'email_verified_at' => now(),
                'is_verified' => true,
                'verification_status' => 'verified',
                'current_market_id' => 1,
                'current_product_index' => 0,
                'unlocked_markets' => json_encode([1]),
                'balance' => 100.00,
            ]);
            
            $this->command->info('تم إنشاء المستخدم الجديد: ' . $user->email);
        }
    }
}
