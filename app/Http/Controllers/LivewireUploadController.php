<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Helpers\FileUploadHelper;

class LivewireUploadController extends Controller
{
    protected $fileHelper;

    public function __construct()
    {
        $this->fileHelper = new FileUploadHelper();
    }

    /**
     * Handle Livewire file uploads with enhanced error handling
     */
    public function handleUpload(Request $request)
    {
        try {
            Log::info('🔥 Livewire Upload Request Started', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'content_type' => $request->header('Content-Type'),
                'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0,
                'user_authenticated' => auth()->check(),
                'user_id' => auth()->id(),
            ]);

            // التحقق من المستخدم
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'error' => 'يجب تسجيل الدخول لرفع الملفات',
                    'code' => 'AUTHENTICATION_REQUIRED'
                ], 401);
            }

            // التحقق من وجود ملفات
            if (!$request->hasFile('files') && !$request->hasFile('file')) {
                return response()->json([
                    'success' => false,
                    'error' => 'لم يتم إرسال أي ملفات',
                    'code' => 'NO_FILES_UPLOADED'
                ], 400);
            }

            $uploadedFiles = [];
            $errors = [];

            // معالجة الملفات
            $files = $request->hasFile('files') ? $request->file('files') : [$request->file('file')];
            
            foreach ($files as $index => $file) {
                if (!$file || !$file->isValid()) {
                    $errors[] = "الملف رقم {$index} غير صالح";
                    continue;
                }

                try {
                    // استخدام FileUploadHelper
                    $result = $this->fileHelper->uploadImage($file, [
                        'directory' => 'livewire-uploads',
                        'prefix' => 'lw_' . auth()->id() . '_',
                        'max_size' => 50 * 1024 * 1024, // 50MB
                        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
                        'resize' => false
                    ]);

                    if ($result['success']) {
                        $uploadedFiles[] = [
                            'success' => true,
                            'filename' => $result['filename'],
                            'path' => $result['path'],
                            'url' => $result['url'],
                            'size' => $result['size'],
                            'mime_type' => $result['mime_type'],
                            'original_name' => $file->getClientOriginalName()
                        ];

                        Log::info('✅ Livewire File Upload Success', [
                            'filename' => $result['filename'],
                            'size' => $result['size'],
                            'user_id' => auth()->id()
                        ]);
                    } else {
                        $errors[] = "فشل رفع الملف: " . $result['error'];
                        Log::error('❌ Livewire File Upload Failed', [
                            'error' => $result['error'],
                            'filename' => $file->getClientOriginalName()
                        ]);
                    }

                } catch (\Exception $e) {
                    $errors[] = "خطأ في معالجة الملف: " . $e->getMessage();
                    Log::error('❌ Livewire Upload Exception', [
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);
                }
            }

            // تحضير الاستجابة
            $response = [
                'success' => !empty($uploadedFiles),
                'uploaded_files' => $uploadedFiles,
                'errors' => $errors,
                'total_uploaded' => count($uploadedFiles),
                'total_errors' => count($errors),
                'timestamp' => now()->toISOString(),
                'user_id' => auth()->id()
            ];

            $statusCode = !empty($uploadedFiles) ? 200 : 400;

            Log::info('🏁 Livewire Upload Response', [
                'success' => $response['success'],
                'uploaded_count' => $response['total_uploaded'],
                'error_count' => $response['total_errors'],
                'status_code' => $statusCode
            ]);

            return response()->json($response, $statusCode);

        } catch (\Exception $e) {
            Log::error('💥 Livewire Upload Critical Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'خطأ خطير في السيرفر: ' . $e->getMessage(),
                'code' => 'CRITICAL_ERROR',
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Get upload information for debugging
     */
    public function getUploadInfo(Request $request)
    {
        return response()->json([
            'success' => true,
            'info' => [
                'authenticated' => auth()->check(),
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name ?? null,
                'csrf_token' => csrf_token(),
                'session_id' => session()->getId(),
                'request_signature' => $request->query('signature'),
                'request_expires' => $request->query('expires'),
                'current_time' => time(),
                'php_settings' => [
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                    'post_max_size' => ini_get('post_max_size'),
                    'max_execution_time' => ini_get('max_execution_time'),
                    'memory_limit' => ini_get('memory_limit')
                ],
                'livewire_config' => [
                    'disk' => config('livewire.temporary_file_upload.disk'),
                    'max_size' => config('livewire.temporary_file_upload.rules'),
                    'directory' => config('livewire.temporary_file_upload.directory'),
                    'middleware' => config('livewire.temporary_file_upload.middleware')
                ]
            ],
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Clean up temporary Livewire files
     */
    public function cleanup(Request $request)
    {
        try {
            $hours = $request->input('hours', 24);
            $directory = 'livewire-uploads';
            
            $files = Storage::disk('public')->allFiles($directory);
            $deleted = 0;
            $cutoff = now()->subHours($hours);

            foreach ($files as $file) {
                $lastModified = Storage::disk('public')->lastModified($file);
                if ($lastModified < $cutoff->timestamp) {
                    Storage::disk('public')->delete($file);
                    $deleted++;
                }
            }

            Log::info("🧹 Livewire Cleanup Completed", [
                'deleted_files' => $deleted,
                'hours_old' => $hours,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'deleted_count' => $deleted,
                'hours' => $hours,
                'message' => "تم حذف {$deleted} ملف أقدم من {$hours} ساعة"
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Livewire Cleanup Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'فشل في تنظيف الملفات: ' . $e->getMessage()
            ], 500);
        }
    }
}
