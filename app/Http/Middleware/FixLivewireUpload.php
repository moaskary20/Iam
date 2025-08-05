<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FixLivewireUpload
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق إذا كان هذا طلب رفع Livewire
        if ($request->is('livewire/upload-file')) {
            // إضافة headers مطلوبة
            $request->headers->set('Accept', 'application/json');
            
            // التأكد من وجود CSRF token
            if (!$request->hasHeader('X-CSRF-TOKEN') && session()->has('_token')) {
                $request->headers->set('X-CSRF-TOKEN', session()->token());
            }
            
            // إضافة user agent إذا مفقود
            if (!$request->hasHeader('User-Agent')) {
                $request->headers->set('User-Agent', 'Livewire File Upload');
            }
        }

        return $next($request);
    }
}
