<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class NewAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // إنشاء المستخدم الجديد مع كل الحقول المطلوبة
        $user = User::updateOrCreate([
            'email' => 'mo.askary20@gmail.com'
        ], [
            'first_name' => 'محمد',
            'last_name' => 'العسكري',
            'name' => 'محمد العسكري',
            'email' => 'mo.askary20@gmail.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'is_verified' => true,
            'verification_status' => 'verified',
            'current_market_id' => 1,
            'current_product_index' => 0,
            'purchased_products' => json_encode([]),
            'unlocked_markets' => json_encode([1]),
            'balance' => 1000.00,
            'balance_visible' => true,
            'progress_data' => json_encode([]),
            'level' => 1,
            'experience_points' => 0,
            'achievements' => json_encode([]),
            'last_activity' => now(),
        ]);

        // التأكد من وجود دور admin
        $adminRole = Role::firstOrCreate([
            'name' => 'admin'
        ], [
            'display_name' => 'مدير النظام',
            'description' => 'مدير النظام مع كامل الصلاحيات',
            'is_active' => true,
            'priority' => 1
        ]);

        // التأكد من وجود صلاحية access_admin
        $accessAdminPermission = Permission::firstOrCreate([
            'name' => 'access_admin'
        ], [
            'display_name' => 'الوصول للوحة التحكم',
            'description' => 'صلاحية الوصول للوحة التحكم',
            'is_active' => true
        ]);

        // ربط الصلاحية بالدور
        if (!$adminRole->permissions()->where('permission_id', $accessAdminPermission->id)->exists()) {
            $adminRole->permissions()->attach($accessAdminPermission->id);
        }

        // تعيين الدور للمستخدم
        if (!$user->roles()->where('role_id', $adminRole->id)->exists()) {
            $user->roles()->attach($adminRole->id);
        }

        // إنشاء محفظة للمستخدم إذا لم تكن موجودة
        if (!$user->wallet) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 1000.00
            ]);
            $this->command->info('✅ تم إنشاء محفظة للمستخدم');
        }

        $this->command->info('📧 تم إنشاء المستخدم الجديد:');
        $this->command->info('Email: mo.askary20@gmail.com');
        $this->command->info('Password: admin123');
        $this->command->info('Status: Verified Admin');
        $this->command->info('Role: ' . $adminRole->name);
        $this->command->info('Balance: 1000.00');
        
        // التحقق من الصلاحيات
        $this->command->info('=== تحقق من الصلاحيات ===');
        $this->command->info('Can access panel: ' . ($user->fresh()->canAccessPanel(app(\Filament\Panel::class)) ? 'Yes' : 'No'));
        $this->command->info('Is admin: ' . ($user->fresh()->isAdmin() ? 'Yes' : 'No'));
    }
}
