<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FileUploadHelper;

class FileUploadController extends Controller
{
    /**
     * رفع صورة واحدة
     */
    public function uploadSingle(Request $request): JsonResponse
    {
        try {
            // التحقق من وجود ملف
            if (!$request->hasFile('file') && !$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'error' => 'لم يتم العثور على ملف للرفع'
                ], 400);
            }

            $file = $request->file('file') ?? $request->file('image');
            $directory = $request->input('directory', 'uploads');
            
            // خيارات الرفع
            $options = [
                'disk' => 'public',
                'resize' => $request->boolean('resize', false),
                'max_width' => $request->input('max_width', 1920),
                'max_height' => $request->input('max_height', 1080),
                'quality' => $request->input('quality', 85),
                'prefix' => $request->input('prefix', '')
            ];

            $result = FileUploadHelper::uploadImage($file, $directory, $options);

            if ($result['success']) {
                Log::info('Single file uploaded successfully', [
                    'user_id' => Auth::id(),
                    'original_name' => $result['original_name'],
                    'new_path' => $result['path']
                ]);

                return response()->json($result);
            } else {
                return response()->json($result, 400);
            }

        } catch (\Exception $e) {
            Log::error('Single file upload failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'خطأ في رفع الملف: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * رفع ملفات متعددة
     */
    public function uploadMultiple(Request $request): JsonResponse
    {
        try {
            if (!$request->hasFile('files')) {
                return response()->json([
                    'success' => false,
                    'error' => 'لم يتم العثور على ملفات للرفع'
                ], 400);
            }

            $files = $request->file('files');
            $directory = $request->input('directory', 'uploads');
            $uploadedFiles = [];
            $errors = [];

            // خيارات الرفع
            $options = [
                'disk' => 'public',
                'resize' => $request->boolean('resize', false),
                'max_width' => $request->input('max_width', 1920),
                'max_height' => $request->input('max_height', 1080),
                'quality' => $request->input('quality', 85),
                'prefix' => $request->input('prefix', '')
            ];

            foreach ($files as $index => $file) {
                if ($file && $file->isValid()) {
                    $result = FileUploadHelper::uploadImage($file, $directory, $options);
                    
                    if ($result['success']) {
                        $uploadedFiles[] = $result;
                    } else {
                        $errors[] = [
                            'file_index' => $index,
                            'file_name' => $file->getClientOriginalName(),
                            'error' => $result['error']
                        ];
                    }
                }
            }

            Log::info('Multiple files upload completed', [
                'user_id' => Auth::id(),
                'total_files' => count($files),
                'successful_uploads' => count($uploadedFiles),
                'failed_uploads' => count($errors)
            ]);

            return response()->json([
                'success' => true,
                'uploaded_files' => $uploadedFiles,
                'errors' => $errors,
                'summary' => [
                    'total' => count($files),
                    'successful' => count($uploadedFiles),
                    'failed' => count($errors)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Multiple files upload failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'خطأ في رفع الملفات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * رفع صورة للسلايدر
     */
    public function uploadSliderImage(Request $request): JsonResponse
    {
        try {
            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'error' => 'لم يتم العثور على صورة للرفع'
                ], 400);
            }

            $file = $request->file('image');
            
            $options = [
                'disk' => 'public',
                'resize' => true,
                'max_width' => 1920,
                'max_height' => 1080,
                'quality' => 90,
                'prefix' => 'slider'
            ];

            $result = FileUploadHelper::uploadImage($file, 'sliders', $options);

            if ($result['success']) {
                Log::info('Slider image uploaded successfully', [
                    'user_id' => Auth::id(),
                    'original_name' => $result['original_name'],
                    'new_path' => $result['path']
                ]);

                return response()->json($result);
            } else {
                return response()->json($result, 400);
            }

        } catch (\Exception $e) {
            Log::error('Slider image upload failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'خطأ في رفع صورة السلايدر: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * رفع صورة شخصية
     */
    public function uploadProfilePhoto(Request $request): JsonResponse
    {
        try {
            if (!$request->hasFile('photo')) {
                return response()->json([
                    'success' => false,
                    'error' => 'لم يتم العثور على صورة للرفع'
                ], 400);
            }

            $file = $request->file('photo');
            
            $options = [
                'disk' => 'public',
                'resize' => true,
                'max_width' => 800,
                'max_height' => 800,
                'quality' => 85,
                'prefix' => 'profile_' . Auth::id()
            ];

            $result = FileUploadHelper::uploadImage($file, 'profile_photos', $options);

            if ($result['success']) {
                // حذف الصورة الشخصية القديمة إذا كانت موجودة
                $user = Auth::user();
                if ($user && $user->profile_photo_path) {
                    FileUploadHelper::deleteFile($user->profile_photo_path);
                }

                // تحديث مسار الصورة في قاعدة البيانات
                if ($user) {
                    $user->update(['profile_photo_path' => $result['path']]);
                }

                Log::info('Profile photo uploaded successfully', [
                    'user_id' => Auth::id(),
                    'original_name' => $result['original_name'],
                    'new_path' => $result['path']
                ]);

                return response()->json($result);
            } else {
                return response()->json($result, 400);
            }

        } catch (\Exception $e) {
            Log::error('Profile photo upload failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'خطأ في رفع الصورة الشخصية: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف ملف
     */
    public function deleteFile(Request $request): JsonResponse
    {
        try {
            $path = $request->input('path');
            $disk = $request->input('disk', 'public');

            if (empty($path)) {
                return response()->json([
                    'success' => false,
                    'error' => 'مسار الملف مطلوب'
                ], 400);
            }

            $deleted = FileUploadHelper::deleteFile($path, $disk);

            if ($deleted) {
                Log::info('File deleted successfully', [
                    'user_id' => Auth::id(),
                    'path' => $path,
                    'disk' => $disk
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف الملف بنجاح'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'فشل في حذف الملف'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('File deletion failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'خطأ في حذف الملف: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * الحصول على معلومات ملف
     */
    public function getFileInfo(Request $request): JsonResponse
    {
        try {
            $path = $request->input('path');
            $disk = $request->input('disk', 'public');

            if (empty($path)) {
                return response()->json([
                    'success' => false,
                    'error' => 'مسار الملف مطلوب'
                ], 400);
            }

            $info = FileUploadHelper::getFileInfo($path, $disk);

            if ($info) {
                return response()->json([
                    'success' => true,
                    'file_info' => $info
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'الملف غير موجود'
                ], 404);
            }

        } catch (\Exception $e) {
            Log::error('Get file info failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'خطأ في الحصول على معلومات الملف: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تنظيف الملفات المؤقتة
     */
    public function cleanupTempFiles(Request $request): JsonResponse
    {
        try {
            $hours = $request->input('hours', 24);
            $deletedCount = FileUploadHelper::cleanupTempFiles($hours);

            Log::info('Temp files cleanup completed', [
                'user_id' => Auth::id(),
                'deleted_count' => $deletedCount,
                'hours' => $hours
            ]);

            return response()->json([
                'success' => true,
                'deleted_count' => $deletedCount,
                'message' => "تم حذف {$deletedCount} ملف مؤقت"
            ]);

        } catch (\Exception $e) {
            Log::error('Temp files cleanup failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'خطأ في تنظيف الملفات المؤقتة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * اختبار رفع الملفات
     */
    public function testUpload(): JsonResponse
    {
        try {
            $testData = [
                'php_settings' => [
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                    'post_max_size' => ini_get('post_max_size'),
                    'max_execution_time' => ini_get('max_execution_time'),
                    'memory_limit' => ini_get('memory_limit'),
                    'max_file_uploads' => ini_get('max_file_uploads')
                ],
                'storage_info' => [
                    'storage_linked' => is_link(public_path('storage')),
                    'storage_path_exists' => file_exists(storage_path('app/public')),
                    'livewire_tmp_exists' => file_exists(storage_path('app/public/livewire-tmp')),
                    'permissions' => [
                        'storage_writable' => is_writable(storage_path('app/public')),
                        'livewire_tmp_writable' => is_writable(storage_path('app/public/livewire-tmp'))
                    ]
                ],
                'user_info' => [
                    'authenticated' => Auth::check(),
                    'user_id' => Auth::id()
                ]
            ];

            return response()->json([
                'success' => true,
                'message' => 'اختبار رفع الملفات - جميع البيانات',
                'test_data' => $testData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'خطأ في اختبار رفع الملفات: ' . $e->getMessage()
            ], 500);
        }
    }
}
