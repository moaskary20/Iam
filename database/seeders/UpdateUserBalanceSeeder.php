<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateUserBalanceSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Update existing demo user with balance
        $demoUser = User::where('email', 'demo@example.com')->first();
        
        if ($demoUser) {
            $demoUser->update([
                'balance' => 50.00 // رصيد افتراضي أقل من 100 لاختبار السوق المفتوح
            ]);
            
            $this->command->info('تم تحديث رصيد المستخدم التجريبي: 50 دولار');
        }
        
        // Update admin user as well
        $adminUser = User::where('email', 'admin@admin.com')->first();
        
        if ($adminUser) {
            $adminUser->update([
                'balance' => 150.00 // رصيد كافي للإدارة
            ]);
            
            $this->command->info('تم تحديث رصيد المستخدم الإداري: 150 دولار');
        }
    }
}
