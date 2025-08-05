<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FixLivewireSSL
{
    /**
     * Handle an incoming request.
     * 
     * هذا الـ middleware يحل مشاكل Livewire مع Let's Encrypt SSL
     */
    public function handle(Request $request, Closure $next): Response
    {
        // إصلاح مشاكل Livewire مع Let's Encrypt
        $this->fixLetsEncryptSSL($request);
        
        // إصلاح مشاكل Alpine.js expressions
        $this->setupAlpineJsEnvironment();
        
        $response = $next($request);
        
        // إضافة headers للـ response لحل مشاكل Mixed Content
        $this->addSecurityHeaders($response);
        
        return $response;
    }
    
    /**
     * إصلاح مشاكل Livewire مع Let's Encrypt SSL
     */
    protected function fixLetsEncryptSSL(Request $request): void
    {
        // إذا كان هناك Livewire request، تأكد من إعدادات Let's Encrypt الصحيحة
        if ($request->header('X-Livewire')) {
            
            // مع Let's Encrypt، التحقق من SSL مباشر
            $forwardedProto = $request->header('X-Forwarded-Proto');
            $isDirectSSL = $request->isSecure();
            
            if ($forwardedProto === 'https' || $isDirectSSL || env('FORCE_HTTPS', false)) {
                // إعداد الـ scheme بشكل صحيح للـ Let's Encrypt
                if (!$request->isSecure()) {
                    $request->server->set('HTTPS', 'on');
                    $request->server->set('SERVER_PORT', 443);
                    $request->server->set('REQUEST_SCHEME', 'https');
                    
                    // تحديث $_SERVER globals أيضاً
                    $_SERVER['HTTPS'] = 'on';
                    $_SERVER['SERVER_PORT'] = 443;
                    $_SERVER['REQUEST_SCHEME'] = 'https';
                }
                
                // إعداد headers للـ Livewire
                if (!$request->header('X-Forwarded-Proto')) {
                    $request->headers->set('X-Forwarded-Proto', 'https');
                }
            }
            
            // إعداد الـ host بشكل صحيح
            if (!$request->header('Host') && env('APP_URL')) {
                $host = parse_url(env('APP_URL'), PHP_URL_HOST);
                if ($host) {
                    $request->headers->set('Host', $host);
                }
            }
        }
    }
    
    /**
     * إعداد بيئة Alpine.js لتجنب الأخطاء
     */
    protected function setupAlpineJsEnvironment(): void
    {
        // تجنب أخطاء undefined variables في Alpine.js
        if (!defined('ALPINE_JS_ENV_SETUP')) {
            define('ALPINE_JS_ENV_SETUP', true);
            
            // تعيين متغيرات افتراضية لتجنب أخطاء Alpine.js
            $GLOBALS['alpineDefaults'] = [
                'store' => [],
                'sidebar' => ['isOpen' => false],
                'table' => [],
                'selectedRecords' => [],
                'groupIsCollapsed' => false
            ];
        }
    }
    
    /**
     * إضافة security headers للاستجابة
     */
    protected function addSecurityHeaders(Response $response): void
    {
        // منع Mixed Content warnings
        $response->headers->set('Content-Security-Policy', "upgrade-insecure-requests");
        
        // إعداد HTTPS redirect header إذا لزم الأمر
        if (env('FORCE_HTTPS', false) && !request()->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
        
        // إعداد headers لـ Livewire
        if (request()->header('X-Livewire')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }
    }
}
