<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // التحقق من تسجيل الدخول
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'غير مصرح له بالوصول'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();

        // إذا كان المستخدم Super Admin، السماح بكل شيء
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // التحقق من وجود أي من الأدوار المطلوبة
        if (!empty($roles) && !$user->hasAnyRole($roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'ليس لديك الدور المطلوب للوصول لهذا المورد',
                    'required_roles' => $roles,
                    'user_roles' => $user->roles->pluck('name')->toArray()
                ], 403);
            }
            
            abort(403, 'ليس لديك الدور المطلوب للوصول لهذا المورد');
        }

        return $next($request);
    }
}
