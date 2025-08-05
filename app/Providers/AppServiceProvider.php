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
        // إجبار التطبيق على استخدام HTTPS في وضع الإنتاج
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
        
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
}
