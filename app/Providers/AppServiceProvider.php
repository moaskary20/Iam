<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // إعدادات Cloudflare Flexible SSL
        $this->configureCloudflareFlexibleSSL();
        
        // حل مؤقت لمشكلة array offset errors في Livewire
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        
        // تسجيل Blade Directives للأدوار والصلاحيات
        $this->registerBladeDirectives();
        
        set_error_handler(function ($severity, $message, $file, $line) {
            // تجاهل أخطاء array offset على null فقط
            if (str_contains($message, 'Trying to access array offset on value of type null')) {
                // تسجيل الخطأ للمراجعة لكن لا توقف التطبيق
                \Log::debug('Array offset on null detected and ignored', [
                    'message' => $message,
                    'file' => basename($file),
                    'line' => $line
                ]);
                return true; // تجاهل الخطأ
            }
            
            // تجاهل أخطاء undefined array key أيضاً
            if (str_contains($message, 'Undefined array key') || str_contains($message, 'Undefined index')) {
                \Log::debug('Undefined array key detected and ignored', [
                    'message' => $message,
                    'file' => basename($file),
                    'line' => $line
                ]);
                return true;
            }
            
            // تجاهل أخطاء WhitespacePathNormalizer مع null path
            if (str_contains($message, 'WhitespacePathNormalizer::normalizePath()') && str_contains($message, 'must be of type string, null given')) {
                \Log::debug('WhitespacePathNormalizer null path detected and ignored', [
                    'message' => $message,
                    'file' => basename($file),
                    'line' => $line
                ]);
                return true;
            }
            
            // تجاهل أخطاء type errors مع null values في Livewire
            if (str_contains($message, 'must be of type string, null given') && str_contains($file, 'livewire')) {
                \Log::debug('Livewire null type error detected and ignored', [
                    'message' => $message,
                    'file' => basename($file),
                    'line' => $line
                ]);
                return true;
            }
            
            // للأخطاء الأخرى، استخدم error handler الافتراضي
            return false;
        });
    }
    
    /**
     * تسجيل Blade Directives للأدوار والصلاحيات
     */
    private function registerBladeDirectives(): void
    {
        // directive للتحقق من الصلاحيات
        \Blade::if('permission', function (...$permissions) {
            if (!auth()->check()) {
                return false;
            }
            
            $user = auth()->user();
            
            // إذا كان Super Admin، السماح بكل شيء
            if ($user->isSuperAdmin()) {
                return true;
            }
            
            return $user->hasAnyPermission($permissions);
        });
        
        // directive للتحقق من الأدوار
        \Blade::if('role', function (...$roles) {
            if (!auth()->check()) {
                return false;
            }
            
            $user = auth()->user();
            
            // إذا كان Super Admin، السماح بكل شيء
            if ($user->isSuperAdmin()) {
                return true;
            }
            
            return $user->hasAnyRole($roles);
        });
        
        // directive للتحقق من كونه Super Admin
        \Blade::if('superadmin', function () {
            return auth()->check() && auth()->user()->isSuperAdmin();
        });
        
        // directive للتحقق من كونه Admin
        \Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });
    }
    
    /**
     * إعداد Cloudflare Flexible SSL
     */
    protected function configureCloudflareFlexibleSSL(): void
    {
        // مع Flexible SSL، Cloudflare يتصل بالسيرفر عبر HTTP
        // لكن المستخدمين يصلون عبر HTTPS
        
        // إجبار HTTPS للـ URLs في حالة وجود X-Forwarded-Proto
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            URL::forceScheme('https');
            $_SERVER['HTTPS'] = 'on';
        }
        
        // إجبار HTTPS إذا كان الموقع في production
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
        
        // إعداد إضافي للتأكد من عمل asset() بشكل صحيح
        if (env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
        
        // إعداد خاص للـ Livewire مع Flexible SSL
        if (class_exists(\Livewire\Livewire::class)) {
            \Livewire\Livewire::setUpdateRoute(function ($handle) {
                return \Illuminate\Support\Facades\Route::post('/livewire/update', $handle)
                    ->middleware(['web']);
            });
        }
    }
}
