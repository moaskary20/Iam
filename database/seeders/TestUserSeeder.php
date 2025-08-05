<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مستخدم تجريبي للاختبار
        User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'name' => 'Test User',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        $this->command->info("✅ تم إنشاء المستخدمين التجريبيين:");
        $this->command->info("   📧 test@example.com / password123");
        $this->command->info("   📧 admin@example.com / admin123");
    }
}
