<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // إعداد مسارات Livewire المخصصة
        $this->configureRoutes();
        
        // إعداد Livewire للعمل مع Filament
        $this->configureLivewire();
    }

    /**
     * Configure Livewire routes.
     */
    protected function configureRoutes(): void
    {
        // تأكد من أن مسارات Livewire تعمل بشكل صحيح
        Livewire::setUpdateRoute(function ($handle) {
            return \Route::post('/livewire/update', $handle)
                ->middleware(['web']);
        });

        // مسار JavaScript الخاص بـ Livewire
        Livewire::setScriptRoute(function ($handle) {
            return \Route::get('/livewire/livewire.js', $handle);
        });
    }

    /**
     * Configure Livewire settings.
     */
    protected function configureLivewire(): void
    {
        // إعدادات عامة لـ Livewire
        config([
            'livewire.temporary_file_upload.disk' => 'public',
            'livewire.temporary_file_upload.directory' => 'livewire-tmp',
            'livewire.temporary_file_upload.rules' => ['max:51200'], // 50MB
        ]);
    }
}
