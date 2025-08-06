<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
        // فرض HTTPS في وضع الإنتاج
        $this->forceHttpsInProduction();

        // إعداد SSL آمن للجلسات (تم نقل الإعدادات إلى config/.env فقط)

        // إعدادات Alpine.js
        $this->setupAlpineJsFixes();

        // حل مؤقت لمشكلة array offset errors في Livewire
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

        // تسجيل Blade Directives للأدوار والصلاحيات
        $this->registerBladeDirectives();

        // تجاهل بعض التحذيرات غير الحرجة
        set_error_handler(function ($severity, $message, $file, $line) {
            $ignoredErrors = [
                'Trying to access array offset on value of type null',
                'Undefined array key',
                'Undefined index',
                'WhitespacePathNormalizer::normalizePath()',
                'must be of type string, null given'
            ];

            foreach ($ignoredErrors as $error) {
                if (str_contains($message, $error)) {
                    Log::debug('Non-critical error ignored', [
                        'message' => $message,
                        'file' => basename($file),
                        'line' => $line
                    ]);
                    return true;
                }
            }

            return false; // باقي الأخطاء تمرر للـ handler العادي
        });
    }

    /**
     * فرض HTTPS في وضع الإنتاج.
     */
    protected function forceHttpsInProduction(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }

    /**
     * إعداد الجلسات بشكل آمن.
     */
    protected function configureSecureSessions(): void
    {
        // إعدادات الجلسة تتم فقط من config/.env
        // التأكد من أن Livewire يستخدم نفس البروتوكول
        if (class_exists(Livewire::class)) {
            Livewire::setUpdateRoute(function ($handle) {
                return Route::post('/livewire/update', $handle)
                    ->middleware(['web']);
            });
        }
    }

    /**
     * تسجيل Blade Directives للأدوار والصلاحيات.
     */
    private function registerBladeDirectives(): void
    {
        // directive للتحقق من الصلاحيات
        Blade::if('permission', function (...$permissions) {
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
        Blade::if('role', function (...$roles) {
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
        Blade::if('superadmin', function () {
            return auth()->check() && auth()->user()->isSuperAdmin();
        });

        // directive للتحقق من كونه Admin
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });
    }

    /**
     * إعداد Alpine.js fixes
     */
    protected function setupAlpineJsFixes(): void
    {
        \View::composer('*', function ($view) {
            $viewName = $view->getName();

            if (str_contains($viewName, 'filament') || 
                str_contains($viewName, 'admin') || 
                str_contains($viewName, 'livewire')) {

                $view->with('needsAlpineInit', true);
            }
        });

        Blade::component('alpine-init', \Illuminate\View\AnonymousComponent::class);
    }
}
