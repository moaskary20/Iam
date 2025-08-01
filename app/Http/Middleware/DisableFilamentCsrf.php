<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisableFilamentCsrf
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تعطيل CSRF للـ Filament login routes فقط
        if ($request->is('admin/login') && $request->isMethod('post')) {
            $request->session()->regenerateToken();
        }
        
        return $next($request);
    }
}
