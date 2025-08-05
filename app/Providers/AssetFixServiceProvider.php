<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

class AssetFixServiceProvider extends ServiceProvider
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
        // إصلاح مشاكل Livewire Assets
        $this->fixLivewireAssets();
        
        // إصلاح مشاكل AdBlocker
        $this->preventAdBlockerIssues();
        
        // إعداد Asset URL الصحيح
        $this->configureAssetUrls();
    }
    
    /**
     * إصلاح مشاكل Livewire Assets
     */
    protected function fixLivewireAssets(): void
    {
        // إضافة route backup للـ Livewire assets
        Route::get('/livewire/livewire.js', function() {
            $content = $this->getLivewireJsContent();
            
            return Response::make($content, 200, [
                'Content-Type' => 'application/javascript',
                'Cache-Control' => 'public, max-age=86400',
                'X-Adblock-Key' => 'bypass',
                'Access-Control-Allow-Origin' => '*'
            ]);
        })->name('livewire.js.backup');
        
        // Route للـ livewire.min.js
        Route::get('/livewire/livewire.min.js', function() {
            $content = $this->getLivewireJsContent(true);
            
            return Response::make($content, 200, [
                'Content-Type' => 'application/javascript',
                'Cache-Control' => 'public, max-age=86400',
                'X-Adblock-Key' => 'bypass'
            ]);
        })->name('livewire.js.min.backup');
    }
    
    /**
     * منع مشاكل AdBlocker
     */
    protected function preventAdBlockerIssues(): void
    {
        // إضافة routes بديلة للملفات المحجوبة
        Route::get('/assets/core.js', function() {
            return Response::make($this->getCoreJsContent(), 200, [
                'Content-Type' => 'application/javascript',
                'X-Adblock-Key' => 'bypass'
            ]);
        })->name('core.js.safe');
        
        Route::get('/assets/framework.js', function() {
            return Response::make($this->getFrameworkJsContent(), 200, [
                'Content-Type' => 'application/javascript',
                'X-Adblock-Key' => 'bypass'
            ]);
        })->name('framework.js.safe');
        
        // Safe asset routes (مقاومة للـ AdBlocker)
        Route::get('/js/app.js', function() {
            $content = file_exists(public_path('js/safe-loader.js')) 
                ? file_get_contents(public_path('js/safe-loader.js'))
                : $this->getCoreJsContent();
                
            return Response::make($content, 200, [
                'Content-Type' => 'application/javascript',
                'Cache-Control' => 'public, max-age=31536000',
                'X-Content-Type-Options' => 'nosniff',
                'Access-Control-Allow-Origin' => '*'
            ]);
        })->name('app.js.safe');

        Route::get('/css/app.css', function() {
            $content = file_exists(public_path('css/safe-styles.css'))
                ? file_get_contents(public_path('css/safe-styles.css'))
                : $this->getBasicCSS();
                
            return Response::make($content, 200, [
                'Content-Type' => 'text/css',
                'Cache-Control' => 'public, max-age=31536000',
                'Access-Control-Allow-Origin' => '*'
            ]);
        })->name('app.css.safe');
        
        // Alternative safe URLs
        Route::get('/safe-assets/js/{file}', function ($file) {
            $safePath = public_path('js/' . $file);
            if (file_exists($safePath) && pathinfo($file, PATHINFO_EXTENSION) === 'js') {
                $content = file_get_contents($safePath);
                return Response::make($content, 200, [
                    'Content-Type' => 'application/javascript',
                    'X-Adblock-Key' => 'bypass'
                ]);
            }
            return abort(404);
        })->name('safe.js');

        Route::get('/safe-assets/css/{file}', function ($file) {
            $safePath = public_path('css/' . $file);
            if (file_exists($safePath) && pathinfo($file, PATHINFO_EXTENSION) === 'css') {
                $content = file_get_contents($safePath);
                return Response::make($content, 200, [
                    'Content-Type' => 'text/css',
                    'X-Adblock-Key' => 'bypass'
                ]);
            }
            return abort(404);
        })->name('safe.css');
    }
    
    /**
     * إعداد Asset URLs الصحيحة
     */
    protected function configureAssetUrls(): void
    {
        // التأكد من Asset URL الصحيح
        if (config('app.env') === 'production') {
            config([
                'app.asset_url' => config('app.url'),
                'livewire.asset_url' => config('app.url')
            ]);
        }
    }
    
    /**
     * الحصول على محتوى Livewire JS
     */
    protected function getLivewireJsContent($minified = false): string
    {
        try {
            $livewirePath = base_path('vendor/livewire/livewire/dist/livewire.js');
            
            if (file_exists($livewirePath)) {
                $content = file_get_contents($livewirePath);
                
                if ($minified) {
                    // تصغير بسيط للـ JS
                    $content = preg_replace('/\s+/', ' ', $content);
                    $content = str_replace(['; ', ' {', '} '], [';', '{', '}'], $content);
                }
                
                return $content;
            }
        } catch (\Exception $e) {
            \Log::error('Error loading Livewire JS: ' . $e->getMessage());
        }
        
        // fallback content
        return $this->getFallbackLivewireJs();
    }
    
    /**
     * الحصول على Core JS content
     */
    protected function getCoreJsContent(): string
    {
        return "
        // Core JavaScript للـ Alpine.js fixes
        window.alpineStores = window.alpineStores || {};
        window.\$store = window.\$store || {};
        
        // إعداد sidebar store
        window.alpineStores.sidebar = {
            isOpen: false,
            groupsCollapsed: {},
            toggle() { this.isOpen = !this.isOpen; }
        };
        
        window.\$store.sidebar = window.alpineStores.sidebar;
        
        console.log('Core JS loaded successfully');
        ";
    }
    
    /**
     * الحصول على Framework JS content
     */
    protected function getFrameworkJsContent(): string
    {
        return "
        // Framework JavaScript
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', window.alpineStores.sidebar);
            console.log('Framework JS initialized');
        });
        ";
    }
    
    /**
     * Fallback Livewire JS
     */
    protected function getFallbackLivewireJs(): string
    {
        return "
        // Fallback Livewire JS
        window.Livewire = window.Livewire || {};
        window.Alpine = window.Alpine || {};
        
        console.log('Livewire fallback loaded');
        ";
    }
    
    /**
     * Basic CSS content
     */
    protected function getBasicCSS(): string
    {
        return "
        /* Basic CSS fallback */
        .hidden { display: none !important; }
        .block { display: block !important; }
        .flex { display: flex !important; }
        
        .fi-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background: white;
            z-index: 50;
            transition: transform 0.3s ease;
            border-right: 1px solid #e5e7eb;
        }
        
        .fi-sidebar.is-open { transform: translateX(0); }
        .fi-sidebar:not(.is-open) { transform: translateX(-100%); }
        
        @media (min-width: 769px) {
            .fi-sidebar {
                width: 256px;
                position: static;
                transform: translateX(0) !important;
            }
        }
        ";
    }
}
