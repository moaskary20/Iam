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
        
        // معالجة خاصة لطلبات Livewire upload
        if (str_contains($request->path(), 'livewire/upload-file')) {
            \Log::info('FilamentCsrfFix: Processing Livewire upload', [
                'method' => $request->method(),
                'path' => $request->path(),
                'content_type' => $request->header('Content-Type'),
                'all_data_keys' => array_keys($request->all()),
                'files_data' => $request->has('files') ? count($request->input('files', [])) : 0,
                'has_direct_file' => $request->hasFile('file'),
                'all_files' => array_keys($request->allFiles())
            ]);
            
            // تحقق من وجود ملفات في البيانات المرسلة
            if ($request->has('files') && is_array($request->input('files'))) {
                $files = $request->input('files');
                \Log::info('Found files in request data', ['files_count' => count($files)]);
                
                // محاولة معالجة الملف
                if (!empty($files) && isset($files[0])) {
                    $firstFile = $files[0];
                    if ($firstFile instanceof \Illuminate\Http\UploadedFile) {
                        \Log::info('Processing uploaded file', [
                            'original_name' => $firstFile->getClientOriginalName(),
                            'size' => $firstFile->getSize(),
                            'mime_type' => $firstFile->getMimeType()
                        ]);
                        
                        // حفظ الملف مباشرة
                        try {
                            $filename = 'upload_' . uniqid() . '_' . time() . '.' . $firstFile->getClientOriginalExtension();
                            $path = $firstFile->storeAs('livewire-tmp', $filename, 'public');
                            
                            $response = [
                                'success' => true,
                                'filename' => $filename,
                                'path' => $path,
                                'url' => asset('storage/' . $path),
                                'size' => $firstFile->getSize()
                            ];
                            
                            \Log::info('File uploaded successfully via FilamentCsrfFix', $response);
                            
                            return response()->json($response, 200);
                            
                        } catch (\Exception $e) {
                            \Log::error('File upload failed in FilamentCsrfFix', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);
                        }
                    }
                }
            }
            
            // تأكد من وجود user مسجل الدخول
            if (!auth()->check()) {
                \Log::warning('Unauthorized upload attempt');
                return response()->json(['error' => 'Unauthenticated'], 401);
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
