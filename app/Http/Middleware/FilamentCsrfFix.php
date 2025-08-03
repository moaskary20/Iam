<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilamentCsrfFix
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // للطلبات POST على admin/login، تأكد من وجود session
        if ($request->is('admin/login') && $request->isMethod('POST')) {
            // تأكد من بدء الجلسة
            if (!$request->hasSession()) {
                $request->setLaravelSession(app('session.store'));
            }
            
            // بدء الجلسة إذا لم تكن مبدوءة
            if (!$request->session()->isStarted()) {
                $request->session()->start();
            }
        }
        
        // السماح برفع الملفات في لوحة الإدارة
        if ($request->is('admin/*')) {
            // تجديد CSRF token للطلبات المتعلقة بالملفات
            if ($request->hasFile('attachment') || $request->hasFile('image') || $request->hasFile('file')) {
                $request->session()->regenerateToken();
            }
            
            // إعداد headers للسيرفر
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');
            
            // إضافة CSRF token إلى الـ headers إذا لم يكن موجوداً
            if (!$request->header('X-CSRF-TOKEN')) {
                $request->headers->set('X-CSRF-TOKEN', csrf_token());
            }
        }
        
        return $next($request);
    }
}
