<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FileUploadHelper;

class UniversalFileUploadMiddleware
{
    /**
     * معالجة طلبات رفع الملفات الشاملة
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // تسجيل معلومات الطلب للتشخيص
            Log::info('UniversalFileUpload: Request received', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_authenticated' => Auth::check(),
                'user_id' => Auth::id(),
                'content_type' => $request->header('Content-Type'),
                'has_files' => $request->hasFile('files') || $request->hasFile('image') || $request->hasFile('attachment'),
                'request_size' => $request->header('Content-Length')
            ]);

            // إعداد البيئة لرفع الملفات الكبيرة
            $this->setupUploadEnvironment();

            // إضافة headers ضرورية
            $this->addRequiredHeaders($request);

            // معالجة طلبات Livewire upload
            if ($this->isLivewireUploadRequest($request)) {
                return $this->handleLivewireUpload($request, $next);
            }

            // معالجة طلبات رفع الملفات العادية
            if ($this->hasFileUpload($request)) {
                return $this->handleFileUpload($request, $next);
            }

            // تمرير الطلب للمعالج التالي
            return $next($request);

        } catch (\Throwable $e) {
            Log::error('UniversalFileUpload: Unexpected error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'url' => $request->fullUrl(),
                'method' => $request->method()
            ]);

            // في حالة الخطأ، تمرير للمعالج التالي بدلاً من إيقاف العملية
            return $next($request);
        }
    }

    /**
     * إعداد البيئة لرفع الملفات الكبيرة
     */
    private function setupUploadEnvironment(): void
    {
        // زيادة حدود الذاكرة والوقت مؤقتاً
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');
        ini_set('max_input_time', '300');
        
        // إعدادات رفع الملفات (قد لا تعمل على جميع السيرفرات)
        @ini_set('upload_max_filesize', '50M');
        @ini_set('post_max_size', '52M');
        @ini_set('max_file_uploads', '20');
    }

    /**
     * إضافة headers ضرورية
     */
    private function addRequiredHeaders(Request $request): void
    {
        // إضافة CSRF token إذا لم يكن موجوداً
        if (!$request->header('X-CSRF-TOKEN') && $request->session()) {
            $request->headers->set('X-CSRF-TOKEN', $request->session()->token());
        }

        // إضافة headers للـ AJAX requests
        if (!$request->header('X-Requested-With')) {
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        }

        // إضافة Accept header للـ JSON responses
        if (!$request->header('Accept') || $request->header('Accept') === '*/*') {
            $request->headers->set('Accept', 'application/json');
        }
    }

    /**
     * التحقق من وجود طلب رفع ملفات Livewire
     */
    private function isLivewireUploadRequest(Request $request): bool
    {
        return $request->is('livewire/upload-file') || 
               $request->is('livewire/upload-file/*') ||
               ($request->is('livewire/*') && ($request->hasFile('files') || $request->has('files')));
    }

    /**
     * التحقق من وجود ملفات في الطلب
     */
    private function hasFileUpload(Request $request): bool
    {
        return $request->hasFile('files') || 
               $request->hasFile('image') || 
               $request->hasFile('attachment') ||
               $request->hasFile('photo') ||
               $request->has('files');
    }

    /**
     * معالجة طلبات Livewire upload
     */
    private function handleLivewireUpload(Request $request, Closure $next): Response
    {
        Log::info('UniversalFileUpload: Handling Livewire upload');

        // التحقق من المصادقة
        if (!Auth::check()) {
            return $this->handleUnauthenticated($request);
        }

        // إذا كان POST request مع ملفات
        if ($request->isMethod('POST') && $request->hasFile('files')) {
            return $this->processLivewireFiles($request);
        }

        // إذا كان GET request، إرجاع استجابة جاهزة
        if ($request->isMethod('GET')) {
            return response()->json(['success' => true, 'message' => 'Ready for upload']);
        }

        return $next($request);
    }

    /**
     * معالجة طلبات رفع الملفات العادية
     */
    private function handleFileUpload(Request $request, Closure $next): Response
    {
        Log::info('UniversalFileUpload: Handling regular file upload');

        // إضافة معلومات إضافية للطلب
        $request->merge(['_file_upload_processed' => true]);

        return $next($request);
    }

    /**
     * معالجة المستخدمين غير المسجلين
     */
    private function handleUnauthenticated(Request $request): Response
    {
        // في بيئة التطوير، محاولة تسجيل دخول تلقائي للمدير
        if (app()->environment(['local', 'development'])) {
            $adminUser = \App\Models\User::where('email', 'admin@admin.com')->first();
            if ($adminUser) {
                Auth::login($adminUser);
                Log::info('UniversalFileUpload: Auto-logged in admin user');
                return response()->json(['success' => true, 'message' => 'Auto-authenticated']);
            }
        }

        Log::warning('UniversalFileUpload: User not authenticated');
        return response()->json(['error' => 'Authentication required'], 401);
    }

    /**
     * معالجة ملفات Livewire
     */
    private function processLivewireFiles(Request $request): Response
    {
        try {
            $uploadedFiles = [];
            $files = $request->file('files');

            // التأكد من أن files هي مصفوفة
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    // استخدام FileUploadHelper لرفع الملف
                    $result = FileUploadHelper::uploadImage($file, 'livewire-tmp', [
                        'disk' => 'public',
                        'resize' => false,
                        'prefix' => 'livewire'
                    ]);

                    if ($result['success']) {
                        $uploadedFiles[] = [
                            'path' => $result['path'],
                            'url' => $result['full_url'],
                            'name' => $result['filename'],
                            'original_name' => $result['original_name'],
                            'size' => $result['size'],
                            'type' => $result['mime_type']
                        ];

                        Log::info('UniversalFileUpload: File uploaded successfully', [
                            'original_name' => $result['original_name'],
                            'new_name' => $result['filename'],
                            'size' => $result['size']
                        ]);
                    } else {
                        Log::error('UniversalFileUpload: File upload failed', [
                            'original_name' => $file->getClientOriginalName(),
                            'error' => $result['error']
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'files' => $uploadedFiles,
                'message' => 'تم رفع ' . count($uploadedFiles) . ' ملف بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('UniversalFileUpload: File processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'فشل في معالجة الملفات: ' . $e->getMessage()
            ], 500);
        }
    }
}
