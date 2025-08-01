<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip verification check for admin routes
        if ($request->is('admin*') || $request->is('filament*')) {
            return $next($request);
        }
        
        $user = Auth::user();
        if ($user && in_array($user->verification_status, ['pending', 'rejected'])) {
            // يمكنك تخصيص الرسالة أو إعادة التوجيه كما تريد
            return response()->view('auth.verification-required', [
                'status' => $user->verification_status
            ]);
        }
        return $next($request);
    }
}
