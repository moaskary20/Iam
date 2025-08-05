<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Illuminate\Support\Facades\Route;

class LivewireUploadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // تخصيص routes الرفع في Livewire
        $this->configureFileUploadRoutes();
        
        // إعداد تنظيف الملفات المؤقتة
        $this->configureFileCleanup();
    }
    
    /**
     * تخصيص routes رفع الملفات
     */
    protected function configureFileUploadRoutes(): void
    {
        // تسجيل route مخصص للرفع مع التحقق من الصلاحيات
        Route::post('/livewire/upload-file', function () {
            // التحقق من الجلسة
            if (!session()->has('_token')) {
                return response()->json(['error' => 'Invalid session'], 401);
            }
            
            // للمستخدمين المسجلين دخول فقط في Filament
            if (auth()->check()) {
                $user = auth()->user();
                // التحقق من صلاحية الوصول لـ Filament
                if (method_exists($user, 'canAccessPanel')) {
                    try {
                        $panel = \Filament\Facades\Filament::getCurrentPanel();
                        if (!$user->canAccessPanel($panel)) {
                            return response()->json(['error' => 'No panel access'], 403);
                        }
                    } catch (\Exception $e) {
                        // في حالة عدم وجود panel context، نسمح للمستخدم المسجل
                    }
                }
            }
            
            // تمرير الطلب لـ Livewire الأصلي
            return app(\Livewire\Features\SupportFileUploads\Controllers\FileUploadHandler::class)
                ->handle(request());
                
        })->middleware(['web'])->name('livewire.upload-file');
    }
    
    /**
     * إعداد تنظيف الملفات المؤقتة
     */
    protected function configureFileCleanup(): void
    {
        // جدولة تنظيف الملفات المؤقتة القديمة
        if (app()->runningInConsole()) {
            return;
        }
        
        // تنظيف كل 24 ساعة
        $lastCleanup = cache()->get('livewire.last_cleanup', 0);
        if (time() - $lastCleanup > 86400) { // 24 hours
            $this->cleanupOldUploads();
            cache()->put('livewire.last_cleanup', time(), 86400);
        }
    }
    
    /**
     * تنظيف الملفات المؤقتة القديمة
     */
    protected function cleanupOldUploads(): void
    {
        try {
            $disk = \Illuminate\Support\Facades\Storage::disk('public');
            $directory = 'livewire-tmp';
            
            if ($disk->exists($directory)) {
                $files = $disk->allFiles($directory);
                $cutoff = now()->subHours(24);
                
                foreach ($files as $file) {
                    if ($disk->lastModified($file) < $cutoff->timestamp) {
                        $disk->delete($file);
                    }
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Livewire cleanup failed: ' . $e->getMessage());
        }
    }
}
