<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LivewireFixMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // إذا كان الطلب GET إلى livewire/upload-file، نحوله إلى POST
        if ($request->isMethod('GET') && str_contains($request->path(), 'livewire/upload-file')) {
            // إرجاع استجابة JSON للإشارة أن المسار متاح
            return response()->json([
                'message' => 'Upload endpoint ready',
                'method' => 'POST',
                'csrf_token' => csrf_token()
            ], 200);
        }

        // إذا كان الطلب GET إلى livewire/preview-file، نتركه يمر
        if ($request->isMethod('GET') && str_contains($request->path(), 'livewire/preview-file')) {
            return $next($request);
        }

        // للطلبات الأخرى، نتابع بشكل طبيعي
        return $next($request);
    }
}
