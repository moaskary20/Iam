<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مستخدم إداري إذا لم يكن موجود
        if (!User::where('email', 'admin@admin.com')->exists()) {
            $user = User::create([
                'first_name' => 'مدير',
                'last_name' => 'النظام',
                'name' => 'مدير النظام',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
                'is_verified' => true,
            ]);
            
            // إنشاء محفظة للمستخدم
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 2450.75,
            ]);
            
            echo "Admin user created successfully\n";
        } else {
            echo "Admin user already exists\n";
        }
    }
}
