<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ServerTestController extends Controller
{
    /**
     * اختبار إعدادات السيرفر للرفع
     */
    public function testUpload()
    {
        $results = [];
        
        // 1. فحص Storage Link
        $results['storage_link'] = [
            'status' => is_link(public_path('storage')),
            'message' => is_link(public_path('storage')) ? 'Storage link موجود' : 'Storage link مفقود - قم بتشغيل php artisan storage:link',
            'path' => public_path('storage')
        ];
        
        // 2. فحص صلاحيات المجلدات
        $storageDir = storage_path('app/public');
        $results['storage_permissions'] = [
            'status' => is_writable($storageDir),
            'message' => is_writable($storageDir) ? 'صلاحيات storage صحيحة' : 'صلاحيات storage خاطئة - قم بتشغيل chmod 775',
            'path' => $storageDir,
            'permissions' => substr(sprintf('%o', fileperms($storageDir)), -4)
        ];
        
        // 3. فحص مجلدات الرفع
        $uploadDirs = ['sliders', 'profiles', 'products'];
        $results['upload_directories'] = [];
        
        foreach ($uploadDirs as $dir) {
            $path = storage_path("app/public/{$dir}");
            $exists = File::exists($path);
            
            if (!$exists) {
                File::makeDirectory($path, 0775, true);
            }
            
            $results['upload_directories'][$dir] = [
                'status' => File::exists($path) && is_writable($path),
                'message' => File::exists($path) && is_writable($path) ? "مجلد {$dir} جاهز" : "مشكلة في مجلد {$dir}",
                'path' => $path
            ];
        }
        
        // 4. اختبار الرفع الفعلي
        try {
            $testFile = 'test-upload-' . time() . '.txt';
            Storage::disk('public')->put($testFile, 'Test upload content');
            
            $results['actual_upload'] = [
                'status' => Storage::disk('public')->exists($testFile),
                'message' => Storage::disk('public')->exists($testFile) ? 'الرفع يعمل بنجاح' : 'مشكلة في الرفع',
                'file' => $testFile
            ];
            
            // حذف الملف التجريبي
            if (Storage::disk('public')->exists($testFile)) {
                Storage::disk('public')->delete($testFile);
            }
            
        } catch (\Exception $e) {
            $results['actual_upload'] = [
                'status' => false,
                'message' => 'خطأ في الرفع: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
        
        // 5. فحص إعدادات .env
        $results['env_settings'] = [
            'app_url' => config('app.url'),
            'filesystem_disk' => config('filesystems.default'),
            'public_disk_url' => config('filesystems.disks.public.url'),
            'status' => !empty(config('app.url'))
        ];
        
        return response()->json([
            'timestamp' => now()->toDateTimeString(),
            'server_info' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'storage_path' => storage_path(),
                'public_path' => public_path(),
            ],
            'tests' => $results,
            'recommendations' => $this->getRecommendations($results)
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * الحصول على التوصيات لحل المشاكل
     */
    private function getRecommendations($results)
    {
        $recommendations = [];
        
        if (!$results['storage_link']['status']) {
            $recommendations[] = 'قم بتشغيل: php artisan storage:link';
        }
        
        if (!$results['storage_permissions']['status']) {
            $recommendations[] = 'قم بتشغيل: chmod -R 775 storage && chown -R www-data:www-data storage';
        }
        
        if (!$results['actual_upload']['status']) {
            $recommendations[] = 'تحقق من إعدادات السيرفر والصلاحيات';
            $recommendations[] = 'تأكد من أن المجلد storage/app/public قابل للكتابة';
        }
        
        foreach ($results['upload_directories'] as $dir => $result) {
            if (!$result['status']) {
                $recommendations[] = "قم بإنشاء مجلد {$dir}: mkdir -p storage/app/public/{$dir}";
            }
        }
        
        return $recommendations;
    }
    
    /**
     * عرض صفحة الاختبار
     */
    public function showTestPage()
    {
        return view('server-test');
    }
}
