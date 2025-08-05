<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FixAssetsMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * هذا الـ middleware يحل مشاكل Assets وAdBlocker
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // إصلاح مشاكل Assets
        $this->fixAssetHeaders($request, $response);
        
        // إصلاح مشاكل AdBlocker
        $this->preventAdBlocker($request, $response);
        
        // إصلاح مشاكل Livewire Assets
        $this->fixLivewireAssets($request, $response);
        
        return $response;
    }
    
    /**
     * إصلاح headers للـ Assets
     */
    protected function fixAssetHeaders(Request $request, Response $response): void
    {
        // إذا كان الطلب للـ assets
        if ($this->isAssetRequest($request)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('Access-Control-Allow-Origin', '*');
        }
    }
    
    /**
     * منع حجب AdBlocker
     */
    protected function preventAdBlocker(Request $request, Response $response): void
    {
        // إذا كان الطلب للـ Livewire أو Alpine.js
        if ($this->isBlockableAsset($request)) {
            $response->headers->set('X-Adblock-Key', 'bypass');
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
            $response->headers->set('Content-Security-Policy', "script-src 'self' 'unsafe-inline' 'unsafe-eval'");
        }
    }
    
    /**
     * إصلاح مشاكل Livewire Assets
     */
    protected function fixLivewireAssets(Request $request, Response $response): void
    {
        // إذا كان الطلب للـ Livewire
        if (str_contains($request->getPathInfo(), 'livewire')) {
            $response->headers->set('X-Livewire-Safe-Mode', 'true');
            $response->headers->set('Cache-Control', 'public, max-age=86400');
            
            // إصلاح مشاكل CORS
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-Livewire');
        }
    }
    
    /**
     * فحص إذا كان الطلب للـ assets
     */
    protected function isAssetRequest(Request $request): bool
    {
        $path = $request->getPathInfo();
        return preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot|map)$/', $path);
    }
    
    /**
     * فحص إذا كان الطلب لملف قابل للحجب بواسطة AdBlocker
     */
    protected function isBlockableAsset(Request $request): bool
    {
        $path = $request->getPathInfo();
        
        // Livewire files
        if (str_contains($path, 'livewire') && str_contains($path, '.js')) {
            return true;
        }
        
        // Alpine.js files
        if (str_contains($path, 'alpine') && str_contains($path, '.js')) {
            return true;
        }
        
        // App files that might be blocked
        if (preg_match('/(app|admin|filament)\.(js|css)$/', $path)) {
            return true;
        }
        
        return false;
    }
}
