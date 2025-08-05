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
     * قائمة Cloudflare IP ranges لجعل Laravel يثق بها
     * @var array<int, string>|string|null
     */
    protected $proxies = [
        // Cloudflare IPv4 ranges
        '103.21.244.0/22',
        '103.22.200.0/22',
        '103.31.4.0/22',
        '104.16.0.0/13',
        '104.24.0.0/14',
        '108.162.192.0/18',
        '131.0.72.0/22',
        '141.101.64.0/18',
        '162.158.0.0/15',
        '172.64.0.0/13',
        '173.245.48.0/20',
        '188.114.96.0/20',
        '190.93.240.0/20',
        '197.234.240.0/22',
        '198.41.128.0/17',
        
        // Cloudflare IPv6 ranges
        '2400:cb00::/32',
        '2606:4700::/32',
        '2803:f800::/32',
        '2405:b500::/32',
        '2405:8100::/32',
        '2a06:98c0::/29',
        '2c0f:f248::/32',
        
        // Other common proxies
        '127.0.0.1',
        '10.0.0.0/8',
        '172.16.0.0/12',
        '192.168.0.0/16',
    ];

    /**
     * The headers that should be used to detect proxies.
     * 
     * Headers اللي هنستخدمها لاكتشاف المعلومات الصحيحة من Cloudflare
     * @var int
     */
    protected $headers = 
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تطبيق إعدادات Trust Proxies من Laravel
        parent::handle($request, $next);
        
        // إجبار HTTPS إذا كان الموقع يعمل خلف Cloudflare
        $this->forceHttpsScheme($request);
        
        // إضافة معلومات إضافية من Cloudflare headers
        $this->addCloudflareInfo($request);
        
        return $next($request);
    }
    
    /**
     * إجبار HTTPS scheme باستخدام X-Forwarded-Proto header
     * محدث للعمل مع Cloudflare Flexible SSL
     */
    protected function forceHttpsScheme(Request $request): void
    {
        // مع Cloudflare Flexible SSL، الاتصال بين Cloudflare والسيرفر يكون HTTP
        // لكن Cloudflare يرسل X-Forwarded-Proto: https للإشارة أن المستخدم يستخدم HTTPS
        
        $forwardedProto = $request->header('X-Forwarded-Proto');
        
        // إجبار HTTPS إذا كان المستخدم يصل عبر HTTPS (حتى لو السيرفر يستلم HTTP)
        if ($forwardedProto === 'https') {
            $request->server->set('HTTPS', 'on');
            $request->server->set('SERVER_PORT', 443);
            $request->server->set('REQUEST_SCHEME', 'https');
            
            // إضافة متغيرات إضافية لـ Laravel للتأكد من اكتشاف HTTPS
            $_SERVER['HTTPS'] = 'on';
            $_SERVER['SERVER_PORT'] = 443;
            $_SERVER['REQUEST_SCHEME'] = 'https';
        }
        
        // التحقق من Cloudflare SSL header (CF-Visitor)
        $cfVisitor = $request->header('CF-Visitor');
        if ($cfVisitor) {
            $visitor = json_decode($cfVisitor, true);
            if (isset($visitor['scheme']) && $visitor['scheme'] === 'https') {
                $request->server->set('HTTPS', 'on');
                $request->server->set('SERVER_PORT', 443);
                $request->server->set('REQUEST_SCHEME', 'https');
                
                // تحديث متغيرات PHP العامة
                $_SERVER['HTTPS'] = 'on';
                $_SERVER['SERVER_PORT'] = 443;
                $_SERVER['REQUEST_SCHEME'] = 'https';
            }
        }
        
        // للتأكد مع Flexible SSL: إذا كان الطلب من Cloudflare واحتوى على CF-Ray
        // نفترض أنه HTTPS (لأن Cloudflare غالباً ما يستخدم HTTPS للمستخدمين)
        if ($request->header('CF-Ray') && !$request->isSecure()) {
            // فقط إذا لم يكن Laravel يكتشف HTTPS بالفعل
            $request->server->set('HTTPS', 'on');
            $request->server->set('SERVER_PORT', 443);
            $request->server->set('REQUEST_SCHEME', 'https');
            
            $_SERVER['HTTPS'] = 'on';
            $_SERVER['SERVER_PORT'] = 443;
            $_SERVER['REQUEST_SCHEME'] = 'https';
        }
    }
    
    /**
     * إضافة معلومات Cloudflare للـ request
     */
    protected function addCloudflareInfo(Request $request): void
    {
        // إضافة معلومات Cloudflare للـ request attributes
        if ($request->header('CF-Ray')) {
            $request->attributes->set('cloudflare_ray', $request->header('CF-Ray'));
        }
        
        if ($request->header('CF-IPCountry')) {
            $request->attributes->set('visitor_country', $request->header('CF-IPCountry'));
        }
        
        if ($request->header('CF-Connecting-IP')) {
            $request->attributes->set('real_ip', $request->header('CF-Connecting-IP'));
        }
    }
}
