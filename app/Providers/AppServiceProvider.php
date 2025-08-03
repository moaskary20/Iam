<?php

namespace App\Providers;

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
        // إعداد Livewire لدعم GET و POST في مسارات رفع الملفات
        $this->configureLivewireRoutes();
    }

    /**
     * Configure Livewire routes to support both GET and POST methods
     */
    protected function configureLivewireRoutes(): void
    {
        // تعطيل مسارات Livewire الافتراضية وإنشاء مسارات مخصصة
        Livewire::setUpdateRoute(function ($handle) {
            return \Route::post('/livewire/update', $handle)
                ->middleware(['web']);
        });
    }
}
