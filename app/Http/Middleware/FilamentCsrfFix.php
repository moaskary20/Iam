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
        
        return $next($request);
    }
}
