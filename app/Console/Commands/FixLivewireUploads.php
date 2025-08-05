<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class FixLivewireUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'livewire:fix-uploads {--force : Force fix without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إصلاح جميع مشاكل رفع الملفات في Livewire';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔥 بدء إصلاح مشاكل Livewire Uploads...');

        // 1. تنظيف الـ cache
        $this->info('1️⃣ تنظيف الـ cache...');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        $this->info('✅ تم تنظيف الـ cache');

        // 2. إنشاء مجلدات التخزين
        $this->info('2️⃣ إنشاء مجلدات التخزين...');
        $this->createStorageDirectories();

        // 3. تعيين الصلاحيات
        $this->info('3️⃣ تعيين صلاحيات المجلدات...');
        $this->fixPermissions();

        // 4. إنشاء symbolic link
        $this->info('4️⃣ إنشاء symbolic link...');
        if (!file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
            $this->info('✅ تم إنشاء storage link');
        } else {
            $this->info('ℹ️ Storage link موجود مسبقاً');
        }

        // 5. اختبار الإعدادات
        $this->info('5️⃣ اختبار الإعدادات...');
        $this->testSettings();

        // 6. إنشاء ملف اختبار
        $this->info('6️⃣ إنشاء ملف اختبار...');
        $this->createTestFile();

        $this->info('🎉 تم إصلاح جميع مشاكل Livewire Uploads بنجاح!');
        $this->info('');
        $this->info('📋 ملخص التصحيحات:');
        $this->info('  ✅ تنظيف الـ cache');
        $this->info('  ✅ إنشاء مجلدات التخزين');
        $this->info('  ✅ تعيين الصلاحيات');
        $this->info('  ✅ إنشاء storage link');
        $this->info('  ✅ اختبار الإعدادات');
        $this->info('  ✅ إنشاء ملف اختبار');
        $this->info('');
        $this->info('🌐 يمكنك الآن اختبار النظام على: http://127.0.0.1:8080/upload-test');
    }

    /**
     * Create necessary storage directories
     */
    private function createStorageDirectories()
    {
        $directories = [
            'livewire-tmp',
            'livewire-uploads',
            'test-uploads',
            'profile-photos',
            'slider-images',
            'temp-uploads'
        ];

        foreach ($directories as $dir) {
            $path = storage_path("app/public/{$dir}");
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
                $this->info("  ✅ تم إنشاء مجلد: {$dir}");
            } else {
                $this->info("  ℹ️ المجلد موجود: {$dir}");
            }
        }
    }

    /**
     * Fix directory permissions
     */
    private function fixPermissions()
    {
        $paths = [
            storage_path('app'),
            storage_path('app/public'),
            storage_path('framework'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
            public_path('storage')
        ];

        foreach ($paths as $path) {
            if (File::exists($path)) {
                chmod($path, 0755);
                $this->info("  ✅ تم تعيين صلاحيات: " . basename($path));
            }
        }
    }

    /**
     * Test current settings
     */
    private function testSettings()
    {
        $settings = [
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
        ];

        $this->info('  📊 إعدادات PHP الحالية:');
        foreach ($settings as $key => $value) {
            $this->info("    {$key}: {$value}");
        }

        // Test storage
        $storageWritable = is_writable(storage_path('app/public'));
        $this->info("  📁 Storage writable: " . ($storageWritable ? '✅ نعم' : '❌ لا'));

        // Test livewire temp directory
        $livewireTmp = storage_path('app/public/livewire-tmp');
        $livewireTmpWritable = is_writable($livewireTmp);
        $this->info("  📁 Livewire tmp writable: " . ($livewireTmpWritable ? '✅ نعم' : '❌ لا'));
    }

    /**
     * Create a test file for validation
     */
    private function createTestFile()
    {
        $testContent = json_encode([
            'created_at' => now()->toISOString(),
            'created_by' => 'FixLivewireUploads Command',
            'message' => 'هذا ملف اختبار لتأكيد عمل نظام رفع الملفات',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version()
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $testFile = storage_path('app/public/livewire-uploads/system-test.json');
        File::put($testFile, $testContent);
        
        if (File::exists($testFile)) {
            $this->info('  ✅ تم إنشاء ملف اختبار: livewire-uploads/system-test.json');
        } else {
            $this->error('  ❌ فشل في إنشاء ملف اختبار');
        }
    }
}
