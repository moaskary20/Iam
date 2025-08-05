<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class NewAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // إنشاء المستخدم الجديد
        $user = User::firstOrCreate([
            'email' => 'mo.askary20@gmail.com'
        ], [
            'name' => 'Mohamed Askary',
            'password' => Hash::make('newpassword'),
            'email_verified_at' => now(),
        ]);

        // الحصول على دور admin
        $adminRole = Role::where('name', 'admin')->first();
        
        if ($adminRole && !$user->hasRole('admin')) {
            $user->assignRole($adminRole);
            $this->command->info('✅ تم تعيين دور Admin للمستخدم');
        }

        // إنشاء محفظة للمستخدم إذا لم تكن موجودة
        if (!$user->wallet) {
            $user->wallet()->create([
                'balance' => 0
            ]);
            $this->command->info('✅ تم إنشاء محفظة للمستخدم');
        }

        $this->command->info('📧 تم إنشاء المستخدم الجديد:');
        $this->command->info('Email: mo.askary20@gmail.com');
        $this->command->info('Password: newpassword');
        $this->command->info('Role: Admin');
    }
}
