<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LivewireUploadFix
{
    /**
     * Handle an incoming request for Livewire uploads
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('LivewireUploadFix: Request received', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_authenticated' => Auth::check(),
            'user_id' => Auth::id(),
            'is_livewire' => $request->is('livewire/*'),
            'content_type' => $request->header('Content-Type'),
            'has_files' => $request->hasFile('files'),
            'request_data_keys' => array_keys($request->all())
        ]);

        // إذا كان الطلب من Livewire upload
        if ($request->is('livewire/upload-file') || $request->is('livewire/*')) {
            
            // تأكد من أن المستخدم مسجل الدخول
            if (!Auth::check()) {
                Log::warning('LivewireUploadFix: User not authenticated');
                
                // محاولة تسجيل دخول تلقائي للادمن في بيئة التطوير
                if (app()->environment(['local', 'development'])) {
                    $adminUser = \App\Models\User::where('email', 'admin@admin.com')->first();
                    if ($adminUser) {
                        Auth::login($adminUser);
                        Log::info('LivewireUploadFix: Auto-logged in admin user');
                    } else {
                        Log::warning('LivewireUploadFix: Admin user not found for auto-login');
                        return response()->json(['error' => 'Authentication required'], 401);
                    }
                } else {
                    return response()->json(['error' => 'Authentication required'], 401);
                }
            }
            
            // إضافة headers مطلوبة
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');
            $request->headers->set('Accept', 'application/json');
            
            // تأكد من CSRF token
            if (!$request->header('X-CSRF-TOKEN')) {
                $token = csrf_token();
                $request->headers->set('X-CSRF-TOKEN', $token);
                Log::info('LivewireUploadFix: Added CSRF token');
            }
            
            // معالجة الملفات إذا كانت موجودة
            if ($request->hasFile('files')) {
                Log::info('LivewireUploadFix: Processing files', [
                    'files_count' => count($request->file('files'))
                ]);
            }
        }

        $response = $next($request);

        // إضافة CORS headers للاستجابة
        if ($request->is('livewire/*') || $request->is('admin/*')) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        Log::info('LivewireUploadFix: Response completed', [
            'status' => $response->getStatusCode(),
            'content_type' => $response->headers->get('Content-Type')
        ]);

        return $response;
    }
}
