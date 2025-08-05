<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FixFilamentAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // إصلاح مشاكل Filament Authentication
        
        // 1. تسجيل معلومات التشخيص
        if ($request->is('admin*')) {
            Log::info('Filament Request Debug', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'session_id' => $request->session()->getId(),
                'csrf_token' => $request->session()->token(),
                'auth_check' => Auth::check(),
                'auth_user_id' => Auth::id(),
                'headers' => [
                    'host' => $request->header('host'),
                    'x-forwarded-proto' => $request->header('x-forwarded-proto'),
                    'x-forwarded-for' => $request->header('x-forwarded-for'),
                ]
            ]);
        }
        
        // 2. إصلاح session configuration لـ production
        if (config('app.env') === 'production' && $request->is('admin*')) {
            $sessionConfig = [
                'lifetime' => 7200,
                'expire_on_close' => false,
                'encrypt' => false,
                'files' => storage_path('framework/sessions'),
                'connection' => null,
                'table' => 'sessions',
                'store' => null,
                'lottery' => [2, 100],
                'cookie' => config('session.cookie'),
                'path' => '/',
                'domain' => parse_url(config('app.url'), PHP_URL_HOST),
                'secure' => true,
                'http_only' => true,
                'same_site' => 'lax',
            ];
            
            foreach ($sessionConfig as $key => $value) {
                config(["session.{$key}" => $value]);
            }
        }
        
        // 3. إصلاح redirect loops
        if ($request->is('admin/login') && Auth::check()) {
            $user = Auth::user();
            Log::info('User already authenticated, redirecting to dashboard', [
                'user_id' => $user->id,
                'email' => $user->email,
                'can_access_panel' => method_exists($user, 'canAccessPanel') ? $user->canAccessPanel(app(\Filament\Panel::class)) : 'unknown'
            ]);
            
            return redirect('/admin');
        }
        
        return $next($request);
    }
}
