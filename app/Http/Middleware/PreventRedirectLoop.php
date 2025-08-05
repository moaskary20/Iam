<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventRedirectLoop
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // منع redirect loops
        if ($request->header('X-Redirect-Count', 0) > 3) {
            return response('Too many redirects', 400);
        }
        
        $response = $next($request);
        
        // إضافة header لتتبع redirects
        if ($response->isRedirection()) {
            $redirectCount = (int) $request->header('X-Redirect-Count', 0) + 1;
            $response->header('X-Redirect-Count', $redirectCount);
        }
        
        return $response;
    }
}
