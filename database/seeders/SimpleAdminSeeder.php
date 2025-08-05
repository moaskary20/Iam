<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class SimpleAdminSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // حذف المستخدم إذا كان موجود
        User::where('email', 'admin@test.com')->delete();
        
        // إنشاء مستخدم جديد
        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Test',
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('123456'),
            'email_verified_at' => now(),
            'is_verified' => true,
            'verification_status' => 'verified',
            'current_market_id' => 1,
            'current_product_index' => 0,
            'purchased_products' => json_encode([]),
            'unlocked_markets' => json_encode([1]),
            'balance' => 1000.00,
        ]);

        // إنشاء محفظة
        Wallet::create([
            'user_id' => $user->id,
            'balance' => 1000.00
        ]);

        $this->command->info('✅ Simple admin user created:');
        $this->command->info('Email: admin@test.com');
        $this->command->info('Password: 123456');
        
        // اختبار الوصول
        try {
            $panel = app(\Filament\Panel::class);
            $canAccess = $user->canAccessPanel($panel);
            $this->command->info('Can access panel: ' . ($canAccess ? 'YES' : 'NO'));
        } catch (\Exception $e) {
            $this->command->error('Panel test failed: ' . $e->getMessage());
        }
    }
}
