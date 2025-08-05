<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
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

        // التحقق من وجود أي من الصلاحيات المطلوبة
        if (!empty($permissions) && !$user->hasAnyPermission($permissions)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'ليس لديك الصلاحية للوصول لهذا المورد',
                    'required_permissions' => $permissions
                ], 403);
            }
            
            abort(403, 'ليس لديك الصلاحية للوصول لهذا المورد');
        }

        return $next($request);
    }
}
