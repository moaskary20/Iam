<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Role;
use App\Models\Permission;

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
                'email_verified_at' => now(),
                'verification_status' => 'verified',
                'current_market_id' => 1,
                'current_product_index' => 0,
                'purchased_products' => json_encode([]),
                'unlocked_markets' => json_encode([1]),
                'balance' => 2450.75,
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
            
            // إنشاء محفظة للمستخدم
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 2450.75,
            ]);
            
            echo "Admin user created successfully\n";
            echo "Email: admin@admin.com\n";
            echo "Password: password\n";
        } else {
            echo "Admin user already exists\n";
        }
    }
}
