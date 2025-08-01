<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureFilamentAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // إذا لم يكن المستخدم مسجل دخول، توجيهه لصفحة تسجيل الدخول
        if (!Auth::check()) {
            return redirect('/admin-login');
        }

        // التأكد من أن Filament يعرف المستخدم
        try {
            if (!filament()->auth()->check()) {
                filament()->auth()->login(Auth::user());
            }
        } catch (\Exception $e) {
            // في حالة وجود مشكلة، سجل المستخدم مرة أخرى
            filament()->auth()->login(Auth::user());
        }

        return $next($request);
    }
}
