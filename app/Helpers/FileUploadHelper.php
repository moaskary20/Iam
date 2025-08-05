<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileUploadHelper
{
    /**
     * الأنواع المسموحة للصور
     */
    const ALLOWED_IMAGE_TYPES = [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'
    ];

    /**
     * الحد الأقصى لحجم الملف (بالبايت) - 50MB
     */
    const MAX_FILE_SIZE = 50 * 1024 * 1024;

    /**
     * رفع صورة مع معالجة شاملة
     * 
     * @param UploadedFile $file
     * @param string $directory
     * @param array $options
     * @return array|false
     */
    public static function uploadImage(UploadedFile $file, string $directory = 'uploads', array $options = [])
    {
        try {
            // التحقق من صحة الملف
            $validation = self::validateFile($file);
            if (!$validation['valid']) {
                return [
                    'success' => false,
                    'error' => $validation['error']
                ];
            }

            // إعداد الخيارات الافتراضية
            $options = array_merge([
                'disk' => 'public',
                'resize' => false,
                'max_width' => 1920,
                'max_height' => 1080,
                'quality' => 85,
                'format' => null, // null يعني احتفظ بالصيغة الأصلية
                'prefix' => '',
                'watermark' => false
            ], $options);

            // إنشاء اسم ملف آمن وفريد
            $filename = self::generateSafeFilename($file, $options['prefix']);
            
            // إنشاء المجلد إذا لم يكن موجوداً
            $fullDirectory = $directory . '/' . date('Y/m');
            if (!Storage::disk($options['disk'])->exists($fullDirectory)) {
                Storage::disk($options['disk'])->makeDirectory($fullDirectory);
            }

            $filePath = $fullDirectory . '/' . $filename;

            // معالجة الصورة إذا كانت مطلوبة
            if ($options['resize'] || $options['format'] || $options['watermark']) {
                $processedFile = self::processImage($file, $options);
                if ($processedFile) {
                    $stored = Storage::disk($options['disk'])->put($filePath, $processedFile);
                } else {
                    $stored = $file->storeAs($fullDirectory, $filename, $options['disk']);
                }
            } else {
                $stored = $file->storeAs($fullDirectory, $filename, $options['disk']);
            }

            if ($stored) {
                // إرجاع معلومات الملف
                return [
                    'success' => true,
                    'path' => $filePath,
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'url' => Storage::disk($options['disk'])->url($filePath),
                    'full_url' => asset('storage/' . $filePath)
                ];
            }

            return [
                'success' => false,
                'error' => 'فشل في حفظ الملف'
            ];

        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'directory' => $directory
            ]);

            return [
                'success' => false,
                'error' => 'خطأ في رفع الملف: ' . $e->getMessage()
            ];
        }
    }

    /**
     * التحقق من صحة الملف
     */
    private static function validateFile(UploadedFile $file): array
    {
        // التحقق من وجود الملف
        if (!$file->isValid()) {
            return [
                'valid' => false,
                'error' => 'الملف غير صحيح أو تالف'
            ];
        }

        // التحقق من حجم الملف
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            return [
                'valid' => false,
                'error' => 'حجم الملف كبير جداً. الحد الأقصى هو 50 ميجابايت'
            ];
        }

        // التحقق من نوع الملف
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_IMAGE_TYPES)) {
            return [
                'valid' => false,
                'error' => 'نوع الملف غير مدعوم. الأنواع المدعومة: ' . implode(', ', self::ALLOWED_IMAGE_TYPES)
            ];
        }

        // التحقق من MIME type
        $mimeType = $file->getMimeType();
        $allowedMimes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 
            'image/svg+xml', 'image/bmp', 'image/jpg'
        ];
        
        if (!in_array($mimeType, $allowedMimes)) {
            return [
                'valid' => false,
                'error' => 'نوع MIME غير مدعوم'
            ];
        }

        return ['valid' => true];
    }

    /**
     * إنشاء اسم ملف آمن وفريد
     */
    private static function generateSafeFilename(UploadedFile $file, string $prefix = ''): string
    {
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        
        // تنظيف اسم الملف من الأحرف الخاصة والمسافات
        $cleanName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $cleanName = trim($cleanName, '_');
        
        // إذا كان الاسم فارغاً أو قصيراً جداً، استخدم اسم افتراضي
        if (empty($cleanName) || strlen($cleanName) < 3) {
            $cleanName = 'upload';
        }

        // قطع الاسم إذا كان طويلاً جداً
        if (strlen($cleanName) > 50) {
            $cleanName = substr($cleanName, 0, 50);
        }

        // إضافة prefix إذا كان موجوداً
        if (!empty($prefix)) {
            $prefix = preg_replace('/[^a-zA-Z0-9_-]/', '_', $prefix) . '_';
        }

        // إنشاء اسم فريد
        $timestamp = time();
        $random = Str::random(8);
        
        return $prefix . $timestamp . '_' . $random . '_' . $cleanName . '.' . $extension;
    }

    /**
     * معالجة الصورة (تصغير، تغيير صيغة، إلخ)
     */
    private static function processImage(UploadedFile $file, array $options)
    {
        try {
            // هذا يتطلب مكتبة Intervention Image
            // إذا لم تكن مثبتة، سيتم تخطي المعالجة
            if (!class_exists('Intervention\Image\Facades\Image')) {
                return null;
            }

            $image = Image::make($file);

            // تصغير الصورة إذا كان مطلوباً
            if ($options['resize']) {
                $image->resize($options['max_width'], $options['max_height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // تحسين جودة الصورة
            if (isset($options['quality']) && $options['quality'] > 0 && $options['quality'] <= 100) {
                $image->encode(null, $options['quality']);
            }

            return $image->stream();

        } catch (\Exception $e) {
            Log::warning('Image processing failed, using original file', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName()
            ]);
            return null;
        }
    }

    /**
     * حذف ملف
     */
    public static function deleteFile(string $path, string $disk = 'public'): bool
    {
        try {
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->delete($path);
            }
            return true; // الملف غير موجود أصلاً
        } catch (\Exception $e) {
            Log::error('File deletion failed', [
                'path' => $path,
                'disk' => $disk,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * الحصول على معلومات الملف
     */
    public static function getFileInfo(string $path, string $disk = 'public'): array|null
    {
        try {
            if (!Storage::disk($disk)->exists($path)) {
                return null;
            }

            return [
                'path' => $path,
                'size' => Storage::disk($disk)->size($path),
                'last_modified' => Storage::disk($disk)->lastModified($path),
                'url' => Storage::disk($disk)->url($path),
                'full_url' => asset('storage/' . $path)
            ];
        } catch (\Exception $e) {
            Log::error('Get file info failed', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * تنظيف الملفات المؤقتة القديمة
     */
    public static function cleanupTempFiles(int $olderThanHours = 24, string $disk = 'public'): int
    {
        try {
            $tempDir = 'livewire-tmp';
            $cutoffTime = now()->subHours($olderThanHours)->timestamp;
            $deletedCount = 0;

            $files = Storage::disk($disk)->files($tempDir);
            
            foreach ($files as $file) {
                $lastModified = Storage::disk($disk)->lastModified($file);
                if ($lastModified < $cutoffTime) {
                    if (Storage::disk($disk)->delete($file)) {
                        $deletedCount++;
                    }
                }
            }

            Log::info('Temp files cleanup completed', [
                'deleted_count' => $deletedCount,
                'cutoff_hours' => $olderThanHours
            ]);

            return $deletedCount;

        } catch (\Exception $e) {
            Log::error('Temp files cleanup failed', [
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
}
