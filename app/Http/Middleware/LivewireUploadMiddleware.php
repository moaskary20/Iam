<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LivewireUploadMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من أن المستخدم مسجل دخول للوصول لـ Filament
        if (!auth()->check()) {
            return response()->json([
                'error' => 'Unauthorized - Please login first'
            ], 401);
        }

        // التحقق من صلاحيات Filament
        $user = auth()->user();
        if (!$user->canAccessPanel(\Filament\Facades\Filament::getCurrentPanel())) {
            return response()->json([
                'error' => 'Unauthorized - No panel access'
            ], 401);
        }

        // التحقق من وجود CSRF token
        if (!$request->hasValidSignature() && !$request->header('X-CSRF-TOKEN')) {
            // للـ Livewire uploads، نتحقق من session token
            if (!session()->token() || !hash_equals(session()->token(), $request->header('X-CSRF-TOKEN'))) {
                // نسمح للطلبات اللي فيها _token في الـ request
                if (!$request->has('_token') || !hash_equals(session()->token(), $request->input('_token'))) {
                    return response()->json([
                        'error' => 'CSRF token mismatch'
                    ], 419);
                }
            }
        }

        return $next($request);
    }
}
