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
            $user = User::create([
                'first_name' => 'User',
                'last_name' => $i,
                'name' => 'User ' . $i,
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
                'phone' => '0100000000' . $i,
                'country' => 'Egypt',
                'verification_status' => 'approved',
            ]);
            Wallet::create([
                'user_id' => $user->id,
                'balance' => rand(100, 1000),
            ]);
        }
    }
}
