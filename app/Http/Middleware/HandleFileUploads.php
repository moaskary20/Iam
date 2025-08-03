<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleFileUploads
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // للطلبات التي تتضمن رفع ملفات
        if ($request->hasFile('attachment') || $request->hasFile('image') || $request->hasFile('file')) {
            // تعديل حد الذاكرة مؤقتاً
            ini_set('memory_limit', '256M');
            ini_set('max_execution_time', '300');
            
            // تأكد من وجود CSRF token
            if (!$request->header('X-CSRF-TOKEN') && $request->session()) {
                $request->headers->set('X-CSRF-TOKEN', $request->session()->token());
            }
        }

        return $next($request);
    }
}
