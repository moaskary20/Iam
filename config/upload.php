<?php

return [

    /*
    |--------------------------------------------------------------------------
    | File Upload Configuration
    |--------------------------------------------------------------------------
    |
    | إعدادات شاملة لرفع الملفات في التطبيق
    |
    */

    'default_disk' => env('UPLOAD_DEFAULT_DISK', 'public'),

    'max_file_size' => env('UPLOAD_MAX_FILESIZE_BYTES', 50 * 1024 * 1024), // 50MB

    'max_files_per_request' => env('UPLOAD_MAX_FILES_PER_REQUEST', 20),

    'allowed_image_types' => [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'
    ],

    'allowed_mime_types' => [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp', 
        'image/svg+xml', 'image/bmp', 'image/jpg'
    ],

    /*
    |--------------------------------------------------------------------------
    | Directory Structure
    |--------------------------------------------------------------------------
    */

    'directories' => [
        'uploads' => 'uploads',
        'temp' => 'livewire-tmp',
        'sliders' => 'sliders',
        'profiles' => 'profile_photos',
        'users' => 'users',
        'ids' => 'id_images'
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Processing
    |--------------------------------------------------------------------------
    */

    'image_processing' => [
        'auto_resize' => env('UPLOAD_AUTO_RESIZE', false),
        'max_width' => env('UPLOAD_MAX_WIDTH', 1920),
        'max_height' => env('UPLOAD_MAX_HEIGHT', 1080),
        'quality' => env('UPLOAD_QUALITY', 85),
        'format' => env('UPLOAD_FORMAT', null), // null = keep original
    ],

    /*
    |--------------------------------------------------------------------------
    | Specific Configurations
    |--------------------------------------------------------------------------
    */

    'slider' => [
        'max_width' => 1920,
        'max_height' => 1080,
        'quality' => 90,
        'auto_resize' => true
    ],

    'profile' => [
        'max_width' => 800,
        'max_height' => 800,
        'quality' => 85,
        'auto_resize' => true
    ],

    'thumbnail' => [
        'max_width' => 300,
        'max_height' => 300,
        'quality' => 80,
        'auto_resize' => true
    ],

    /*
    |--------------------------------------------------------------------------
    | Cleanup Settings
    |--------------------------------------------------------------------------
    */

    'cleanup' => [
        'temp_files_lifetime_hours' => env('UPLOAD_TEMP_LIFETIME', 24),
        'auto_cleanup' => env('UPLOAD_AUTO_CLEANUP', true),
        'cleanup_on_boot' => env('UPLOAD_CLEANUP_ON_BOOT', false)
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */

    'security' => [
        'scan_uploads' => env('UPLOAD_SCAN_FILES', false),
        'check_file_content' => env('UPLOAD_CHECK_CONTENT', true),
        'sanitize_filenames' => env('UPLOAD_SANITIZE_NAMES', true),
        'max_filename_length' => env('UPLOAD_MAX_FILENAME_LENGTH', 100)
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Settings
    |--------------------------------------------------------------------------
    */

    'performance' => [
        'memory_limit' => env('UPLOAD_MEMORY_LIMIT', '512M'),
        'execution_time' => env('UPLOAD_EXECUTION_TIME', 300),
        'chunk_size' => env('UPLOAD_CHUNK_SIZE', 8192),
        'use_streams' => env('UPLOAD_USE_STREAMS', true)
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */

    'logging' => [
        'enabled' => env('UPLOAD_LOGGING', true),
        'level' => env('UPLOAD_LOG_LEVEL', 'info'),
        'log_file_details' => env('UPLOAD_LOG_DETAILS', true),
        'log_errors_only' => env('UPLOAD_LOG_ERRORS_ONLY', false)
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Messages (Arabic)
    |--------------------------------------------------------------------------
    */

    'messages' => [
        'file_required' => 'يجب اختيار ملف للرفع',
        'file_invalid' => 'الملف غير صحيح أو تالف',
        'file_too_large' => 'حجم الملف كبير جداً. الحد الأقصى هو :max_size ميجابايت',
        'invalid_file_type' => 'نوع الملف غير مدعوم. الأنواع المدعومة: :types',
        'invalid_mime_type' => 'نوع MIME غير مدعوم',
        'upload_failed' => 'فشل في رفع الملف',
        'processing_failed' => 'فشل في معالجة الملف',
        'storage_failed' => 'فشل في حفظ الملف',
        'unauthorized' => 'غير مصرح لك برفع الملفات',
        'upload_success' => 'تم رفع الملف بنجاح',
        'multiple_upload_success' => 'تم رفع :count ملف بنجاح',
        'partial_upload_success' => 'تم رفع :successful من أصل :total ملف'
    ]

];
