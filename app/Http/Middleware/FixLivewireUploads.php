<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class FixLivewireUploads
{
    public function handle(Request $request, Closure $next): Response
    {
        // إضافة try-catch لمنع أي أخطاء غير متوقعة
        try {
            // إذا كان الطلب من Livewire upload
            if ($request->is('livewire/upload-file')) {
                Log::info('FixLivewireUploads: Intercepting Livewire upload request', [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'headers' => $request->headers->all(),
                    'content_type' => $request->header('Content-Type'),
                    'user_authenticated' => auth()->check(),
                    'has_files' => $request->hasFile('files'),
                    'input_files' => $request->has('files'),
                    'request_size' => $request->header('Content-Length')
                ]);

                // تأكد من authentication
                if (!auth()->check()) {
                    Log::warning('FixLivewireUploads: User not authenticated');
                    return response()->json(['error' => 'Authentication required'], 401);
                }

                // إذا كان POST request مع files
                if ($request->isMethod('POST') && ($request->hasFile('files') || $request->has('files'))) {
                    
                    // معالجة multipart/form-data files
                    if ($request->hasFile('files')) {
                        $uploadedFiles = [];
                        $files = $request->file('files');
                        
                        // التأكد من أن files هي مصفوفة
                        if (!is_array($files)) {
                            $files = [$files];
                        }
                        
                        foreach ($files as $file) {
                            if ($file && $file->isValid()) {
                                $originalName = $file->getClientOriginalName();
                                $extension = $file->getClientOriginalExtension();
                                
                                // التأكد من وجود اسم ملف صحيح
                                if (empty($originalName)) {
                                    $originalName = 'upload_' . uniqid() . '.' . ($extension ?: 'jpg');
                                }
                                
                                $filename = time() . '_' . uniqid() . '_' . $originalName;
                                
                                // التأكد من أن filename لا يحتوي على null
                                $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
                                
                                try {
                                    $path = $file->storeAs('livewire-tmp', $filename, 'public');
                                    
                                    // التأكد من أن path ليس null
                                    if ($path) {
                                        $uploadedFiles[] = [
                                            'path' => $path,
                                            'url' => asset('storage/' . $path),
                                            'name' => $filename,
                                            'size' => $file->getSize(),
                                            'type' => $file->getMimeType() ?: 'application/octet-stream'
                                        ];
                                    }
                                } catch (\Exception $e) {
                                    Log::error('File storage failed', [
                                        'filename' => $filename,
                                        'error' => $e->getMessage()
                                    ]);
                                }
                            }
                        }
                        
                        Log::info('FixLivewireUploads: Files processed successfully', [
                            'files_count' => count($uploadedFiles)
                        ]);
                        
                        return response()->json([
                            'success' => true,
                            'files' => $uploadedFiles
                        ]);
                    }
                    
                    // معالجة JSON files structure من Livewire
                    if ($request->has('files') && is_array($request->input('files'))) {
                        $files = $request->input('files');
                        $uploadedFiles = [];
                        
                        foreach ($files as $fileData) {
                            if (is_array($fileData) && isset($fileData['Illuminate\\Http\\UploadedFile'])) {
                                $tempPath = $fileData['Illuminate\\Http\\UploadedFile'];
                                
                                // التأكد من أن tempPath ليس null أو فارغ
                                if (!empty($tempPath) && file_exists($tempPath)) {
                                    $filename = time() . '_' . uniqid() . '_upload.jpg';
                                    
                                    // تنظيف اسم الملف من أي characters غير صالحة
                                    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
                                    
                                    $destinationPath = storage_path('app/public/livewire-tmp/' . $filename);
                                    
                                    // التأكد من وجود المجلد
                                    $dir = dirname($destinationPath);
                                    if (!is_dir($dir)) {
                                        mkdir($dir, 0755, true);
                                    }
                                    
                                    try {
                                        if (copy($tempPath, $destinationPath)) {
                                            $uploadedFiles[] = [
                                                'path' => 'livewire-tmp/' . $filename,
                                                'url' => asset('storage/livewire-tmp/' . $filename),
                                                'name' => $filename,
                                                'size' => filesize($destinationPath),
                                                'type' => mime_content_type($destinationPath) ?: 'application/octet-stream'
                                            ];
                                        }
                                    } catch (\Exception $e) {
                                        Log::error('Livewire temp file copy failed', [
                                            'temp_path' => $tempPath,
                                            'destination' => $destinationPath,
                                            'error' => $e->getMessage()
                                        ]);
                                    }
                                }
                            }
                        }
                        
                        Log::info('FixLivewireUploads: Livewire files processed', [
                            'files_count' => count($uploadedFiles)
                        ]);
                        
                        return response()->json([
                            'success' => true,
                            'files' => $uploadedFiles
                        ]);
                    }
                }
                
                // إذا كان GET request، إرجاع response فارغ
                if ($request->isMethod('GET')) {
                    Log::info('FixLivewireUploads: Handling GET request for upload');
                    return response()->json(['success' => true, 'message' => 'Ready for upload']);
                }
                
                // إذا كان POST بدون files، إرجاع response فارغ  
                if ($request->isMethod('POST') && !$request->hasFile('files') && !$request->has('files')) {
                    Log::info('FixLivewireUploads: POST request without files');
                    return response()->json(['success' => true, 'message' => 'No files to process']);
                }
            }

            // للطلبات الأخرى، تمرير إلى next middleware
            return $next($request);
            
        } catch (\Throwable $e) {
            // في حالة حدوث أي خطأ، سجل الخطأ وتمرير للـ middleware التالي
            Log::error('FixLivewireUploads: Unexpected error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'url' => $request->fullUrl(),
                'method' => $request->method()
            ]);
            
            // تمرير للـ middleware التالي بدلاً من إرجاع خطأ
            return $next($request);
        }
    }
}
