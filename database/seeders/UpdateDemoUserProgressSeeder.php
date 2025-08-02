<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateDemoUserProgressSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Update existing demo user with progress fields
        $demoUser = User::where('email', 'demo@example.com')->first();
        
        if ($demoUser) {
            $demoUser->update([
                'current_market_id' => 1,
                'current_product_index' => 0,
                'purchased_products' => [],
                'unlocked_markets' => [1]
            ]);
            
            $this->command->info('تم تحديث المستخدم التجريبي بحقول التقدم');
        }
        
        // Update admin user as well
        $adminUser = User::where('email', 'admin@admin.com')->first();
        
        if ($adminUser) {
            $adminUser->update([
                'current_market_id' => 1,
                'current_product_index' => 0,
                'purchased_products' => [],
                'unlocked_markets' => [1]
            ]);
            
            $this->command->info('تم تحديث المستخدم الإداري بحقول التقدم');
        }
    }
}
