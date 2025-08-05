<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FixLivewireUploadSignature
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // إذا كان الطلب خاص بـ Livewire upload
        if ($request->is('livewire/upload-file*')) {
            Log::info('Livewire upload request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'headers' => $request->headers->all(),
                'files' => $request->hasFile('files') ? count($request->file('files')) : 0
            ]);

            // تعيين headers مطلوبة لـ Livewire
            $request->headers->set('Accept', 'application/json');
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');
            
            // التأكد من وجود CSRF token
            if (!$request->hasHeader('X-CSRF-TOKEN')) {
                $csrfToken = csrf_token();
                $request->headers->set('X-CSRF-TOKEN', $csrfToken);
            }

            // إضافة معلومات المستخدم إذا كان مسجل دخول
            if (auth()->check()) {
                $request->merge([
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name
                ]);
            }
        }

        $response = $next($request);

        // إضافة headers للاستجابة
        if ($request->is('livewire/upload-file*')) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-CSRF-TOKEN, X-Requested-With');
            
            Log::info('Livewire upload response', [
                'status' => $response->getStatusCode(),
                'content_length' => $response->headers->get('Content-Length', 'unknown')
            ]);
        }

        return $response;
    }
}
