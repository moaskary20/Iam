<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'user:create-admin {email} {password} {name?}';

    /**
     * The console command description.
     */
    protected $description = 'إنشاء مستخدم جديد بصلاحيات Admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->argument('name') ?? 'Admin User';

        // التحقق من وجود المستخدم
        if (User::where('email', $email)->exists()) {
            $this->error("❌ المستخدم موجود بالفعل: {$email}");
            return 1;
        }

        // إنشاء المستخدم
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        // الحصول على دور admin
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $this->error("❌ دور Admin غير موجود! قم بتشغيل RolesAndPermissionsSeeder أولاً");
            return 1;
        }

        // تعيين دور Admin
        $user->assignRole($adminRole);

        // إنشاء محفظة للمستخدم
        $user->wallet()->create([
            'balance' => 0
        ]);

        $this->info('✅ تم إنشاء المستخدم بنجاح:');
        $this->line("📧 Email: {$email}");
        $this->line("🔐 Password: {$password}");
        $this->line("👤 Name: {$name}");
        $this->line("🛡️ Role: Admin");
        $this->line("💰 Wallet: Created with 0 balance");

        return 0;
    }
}
