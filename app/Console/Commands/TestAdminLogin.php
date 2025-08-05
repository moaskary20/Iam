<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TestAdminLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:admin-login {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test admin login functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        
        $this->info('Testing admin login...');
        $this->info('Email: ' . $email);
        $this->info('Password: ' . $password);
        $this->info('');
        
        // البحث عن المستخدم
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error('❌ User not found');
            return;
        }
        
        $this->info('✅ User found: ' . $user->name);
        
        // التحقق من كلمة المرور
        if (!Hash::check($password, $user->password)) {
            $this->error('❌ Password incorrect');
            return;
        }
        
        $this->info('✅ Password correct');
        
        // التحقق من التفعيل
        if (!$user->is_verified) {
            $this->warn('⚠️  User not verified');
        } else {
            $this->info('✅ User verified');
        }
        
        // التحقق من email verification
        if (!$user->email_verified_at) {
            $this->warn('⚠️  Email not verified');
        } else {
            $this->info('✅ Email verified');
        }
        
        // التحقق من الأدوار
        $roles = $user->roles()->get();
        if ($roles->count() > 0) {
            $this->info('✅ User roles: ' . $roles->pluck('name')->implode(', '));
        } else {
            $this->warn('⚠️  No roles assigned');
        }
        
        // التحقق من الصلاحيات
        $permissions = $user->getAllPermissions();
        if ($permissions->count() > 0) {
            $this->info('✅ User permissions: ' . $permissions->pluck('name')->implode(', '));
        } else {
            $this->warn('⚠️  No permissions');
        }
        
        // التحقق من إمكانية الوصول للوحة التحكم
        try {
            $panel = app(\Filament\Panel::class);
            $canAccess = $user->canAccessPanel($panel);
            if ($canAccess) {
                $this->info('✅ Can access admin panel');
            } else {
                $this->error('❌ Cannot access admin panel');
            }
        } catch (\Exception $e) {
            $this->error('❌ Error checking panel access: ' . $e->getMessage());
        }
        
        // اختبار تسجيل الدخول
        try {
            $credentials = ['email' => $email, 'password' => $password];
            if (Auth::attempt($credentials)) {
                $this->info('✅ Auth::attempt successful');
                Auth::logout();
            } else {
                $this->error('❌ Auth::attempt failed');
            }
        } catch (\Exception $e) {
            $this->error('❌ Auth::attempt exception: ' . $e->getMessage());
        }
        
        $this->info('');
        $this->info('=== Summary ===');
        $this->info('User exists: ✅');
        $this->info('Password valid: ✅');
        $this->info('Admin access: ' . ($user->canAccessPanel(app(\Filament\Panel::class)) ? '✅' : '❌'));
        
        if (!$user->canAccessPanel(app(\Filament\Panel::class))) {
            $this->info('');
            $this->warn('❌ Login will fail - user cannot access admin panel');
            $this->info('Make sure user has admin role or access_admin permission');
        }
    }
}
