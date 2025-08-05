<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AlpineJsServiceProvider extends ServiceProvider
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
        // إضافة Alpine.js store defaults إلى جميع الviews
        View::composer('*', function ($view) {
            $view->with('alpineStoreDefaults', [
                'sidebar' => [
                    'isOpen' => false,
                    'groupsCollapsed' => []
                ],
                'table' => [
                    'selectedRecords' => [],
                    'allRecordsSelected' => false
                ]
            ]);
        });
        
        // إضافة Alpine.js initialization script
        View::composer('*', function ($view) {
            $alpineInitScript = "
                <script>
                // Alpine.js initialization مبكر
                window.alpineStores = window.alpineStores || {};
                window.alpineStores.sidebar = {
                    isOpen: false,
                    groupsCollapsed: {},
                    toggle() { this.isOpen = !this.isOpen; }
                };
                
                // إعداد مبكر قبل Alpine.js
                document.addEventListener('alpine:init', () => {
                    Alpine.store('sidebar', window.alpineStores.sidebar);
                    Alpine.store('table', {
                        selectedRecords: [],
                        allRecordsSelected: false
                    });
                });
                </script>
            ";
            
            $view->with('alpineInitScript', $alpineInitScript);
        });
    }
}
