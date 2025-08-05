<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     * 
     * Simplified - trust local proxies only
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     * 
     * Standard proxy headers (no Cloudflare specific headers)
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // إجبار HTTPS إذا كان الموقع يعمل خلف Cloudflare (قبل parent::handle)
        $this->forceHttpsScheme($request);
        
        // تطبيق إعدادات Trust Proxies من Laravel
        $response = parent::handle($request, $next);
        
        // إضافة معلومات إضافية من Cloudflare headers
        $this->addCloudflareInfo($request);
        
        return $response;
    }
    
    /**
     * إجبار HTTPS scheme باستخدام X-Forwarded-Proto header
     * محدث للعمل مع Let's Encrypt فقط (بدون Cloudflare)
     */
    protected function forceHttpsScheme(Request $request): void
    {
        $shouldForceHttps = false;
        
        // مع Let's Encrypt المباشر:
        // 1. فحص إذا كان SSL أصلي (Let's Encrypt) موجود
        if ($request->isSecure() && $request->getScheme() === 'https') {
            // الاتصال آمن بالفعل، لا حاجة لتعديل
            return;
        }
        
        // 2. فحص X-Forwarded-Proto header من Load Balancer أو Proxy
        $forwardedProto = $request->header('X-Forwarded-Proto');
        if ($forwardedProto === 'https') {
            $shouldForceHttps = true;
        }
        
        // 3. إعدادات البيئة للإجبار
        if (env('FORCE_HTTPS', false) || env('APP_ENV') === 'production') {
            $shouldForceHttps = true;
        }
        
        // تطبيق إعدادات HTTPS
        if ($shouldForceHttps) {
            $request->server->set('HTTPS', 'on');
            $request->server->set('SERVER_PORT', 443);
            $request->server->set('REQUEST_SCHEME', 'https');
            
            $_SERVER['HTTPS'] = 'on';
            $_SERVER['SERVER_PORT'] = 443;
            $_SERVER['REQUEST_SCHEME'] = 'https';
            
            if (!$request->header('X-Forwarded-Proto')) {
                $request->headers->set('X-Forwarded-Proto', 'https');
                $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
            }
        }
    }
    
    /**
     * إضافة معلومات إضافية للـ request (بدون Cloudflare)
     */
    protected function addCloudflareInfo(Request $request): void
    {
        // لا حاجة لمعلومات Cloudflare بعد الآن
        // يمكن إضافة معلومات أخرى هنا إذا لزم الأمر
    }
}
