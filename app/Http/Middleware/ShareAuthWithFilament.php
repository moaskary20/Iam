<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ShareAuthWithFilament
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التأكد من أن Filament يعرف المستخدم الحالي
        if (Auth::check()) {
            // تسجيل المستخدم في Filament
            filament()->auth()->login(Auth::user());
        }

        return $next($request);
    }
}
