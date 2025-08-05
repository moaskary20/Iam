<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CloudflareTestController extends Controller
{
    /**
     * اختبار إعدادات Cloudflare وHTTPS
     */
    public function testCloudflare(Request $request): JsonResponse
    {
        $data = [
            'timestamp' => now()->toDateTimeString(),
            'request_info' => [
                'url' => $request->fullUrl(),
                'scheme' => $request->getScheme(),
                'is_secure' => $request->isSecure(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
            'cloudflare_headers' => [
                'cf_ray' => $request->header('CF-Ray'),
                'cf_connecting_ip' => $request->header('CF-Connecting-IP'),
                'cf_country' => $request->header('CF-IPCountry'),
                'cf_visitor' => $request->header('CF-Visitor'),
                'cf_cache_status' => $request->header('CF-Cache-Status'),
            ],
            'forwarded_headers' => [
                'x_forwarded_for' => $request->header('X-Forwarded-For'),
                'x_forwarded_proto' => $request->header('X-Forwarded-Proto'),
                'x_forwarded_host' => $request->header('X-Forwarded-Host'),
                'x_forwarded_port' => $request->header('X-Forwarded-Port'),
                'x_real_ip' => $request->header('X-Real-IP'),
            ],
            'server_vars' => [
                'https' => $_SERVER['HTTPS'] ?? 'not set',
                'server_port' => $_SERVER['SERVER_PORT'] ?? 'not set',
                'request_scheme' => $_SERVER['REQUEST_SCHEME'] ?? 'not set',
                'http_host' => $_SERVER['HTTP_HOST'] ?? 'not set',
            ],
            'app_config' => [
                'app_url' => config('app.url'),
                'force_https' => config('app.force_https', false),
                'asset_url' => config('app.asset_url'),
            ],
            'cloudflare_detection' => [
                'behind_cloudflare' => $this->isBehindCloudflare($request),
                'using_https' => $this->isUsingHttps($request),
                'real_visitor_ip' => $this->getRealVisitorIP($request),
                'visitor_country' => $request->attributes->get('visitor_country'),
                'cloudflare_ray' => $request->attributes->get('cloudflare_ray'),
            ],
            'recommendations' => $this->getRecommendations($request),
        ];

        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * التحقق من أن الموقع يعمل خلف Cloudflare
     */
    private function isBehindCloudflare(Request $request): bool
    {
        return !empty($request->header('CF-Ray')) || 
               !empty($request->header('CF-Connecting-IP')) ||
               !empty($request->header('CF-Visitor'));
    }

    /**
     * التحقق من استخدام HTTPS
     */
    private function isUsingHttps(Request $request): bool
    {
        return $request->isSecure() || 
               $request->header('X-Forwarded-Proto') === 'https' ||
               (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on');
    }

    /**
     * الحصول على IP الحقيقي للزائر
     */
    private function getRealVisitorIP(Request $request): string
    {
        // أولوية للـ CF-Connecting-IP من Cloudflare
        if ($cfIp = $request->header('CF-Connecting-IP')) {
            return $cfIp;
        }

        // ثم X-Forwarded-For
        if ($forwardedFor = $request->header('X-Forwarded-For')) {
            $ips = explode(',', $forwardedFor);
            return trim($ips[0]);
        }

        // ثم X-Real-IP
        if ($realIp = $request->header('X-Real-IP')) {
            return $realIp;
        }

        // أخيراً IP العادي
        return $request->ip();
    }

    /**
     * الحصول على توصيات للإعداد
     */
    private function getRecommendations(Request $request): array
    {
        $recommendations = [];

        if (!$this->isBehindCloudflare($request)) {
            $recommendations[] = 'الموقع لا يبدو أنه يعمل خلف Cloudflare';
        }

        if (!$this->isUsingHttps($request)) {
            $recommendations[] = 'تأكد من تفعيل SSL/TLS في Cloudflare';
            $recommendations[] = 'تأكد من إعداد "Always Use HTTPS" في Cloudflare';
        }

        if (config('app.url') && !str_starts_with(config('app.url'), 'https://')) {
            $recommendations[] = 'قم بتعديل APP_URL في .env ليبدأ بـ https://';
        }

        if (empty($request->header('CF-Connecting-IP'))) {
            $recommendations[] = 'تأكد من أن Cloudflare يمرر CF-Connecting-IP header';
        }

        if ($request->getScheme() !== 'https' && $this->isBehindCloudflare($request)) {
            $recommendations[] = 'Laravel لا يكتشف HTTPS بشكل صحيح - تحقق من TrustProxies middleware';
        }

        return $recommendations;
    }

    /**
     * عرض صفحة اختبار Cloudflare
     */
    public function showTestPage()
    {
        return view('cloudflare-test');
    }
}
