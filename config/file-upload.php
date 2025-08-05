<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Upload Configuration
    |--------------------------------------------------------------------------
    | إعدادات شاملة لرفع الملفات في التطبيق
    */

    // الحد الأقصى لحجم الملف (بالبايت)
    'max_file_size' => env('UPLOAD_MAX_FILESIZE', 50 * 1024 * 1024), // 50MB

    // الأنواع المسموحة للصور
    'allowed_image_types' => [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'
    ],

    // MIME types المسموحة
    'allowed_mime_types' => [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp', 
        'image/svg+xml', 'image/bmp', 'image/jpg'
    ],

    // إعدادات التصغير الافتراضية
    'default_resize_options' => [
        'max_width' => 1920,
        'max_height' => 1080,
        'quality' => 85,
    ],

    // مجلدات التخزين
    'storage_directories' => [
        'uploads' => 'uploads',
        'sliders' => 'sliders',
        'profiles' => 'profile_photos',
        'temp' => 'livewire-tmp',
        'simple' => 'simple-uploads',
    ],

    // إعدادات التنظيف التلقائي
    'cleanup' => [
        'enabled' => true,
        'temp_file_lifetime_hours' => 24,
        'schedule_cleanup' => true, // تنظيف تلقائي مجدول
    ],

    // إعدادات الأمان
    'security' => [
        'scan_for_viruses' => false, // يمكن تفعيلها لاحقاً
        'check_file_content' => true,
        'sanitize_filename' => true,
        'max_filename_length' => 100,
    ],

    // إعدادات الـ Middleware
    'middleware' => [
        'auth_required' => true,
        'csrf_protection' => true,
        'rate_limiting' => true,
        'max_uploads_per_minute' => 30,
    ],

    // إعدادات المعاينة
    'preview' => [
        'generate_thumbnails' => false,
        'thumbnail_sizes' => [
            'small' => [150, 150],
            'medium' => [300, 300],
            'large' => [600, 600],
        ],
    ],

    // إعدادات Livewire
    'livewire' => [
        'enabled' => true,
        'temp_directory' => 'livewire-tmp',
        'max_upload_time_minutes' => 10,
        'cleanup_old_uploads' => true,
    ],

    // إعدادات الـ Logging
    'logging' => [
        'enabled' => true,
        'log_successful_uploads' => true,
        'log_failed_uploads' => true,
        'log_file_operations' => true,
    ],

    // رسائل الخطأ المخصصة
    'error_messages' => [
        'file_too_large' => 'حجم الملف كبير جداً. الحد الأقصى هو :max_size ميجابايت',
        'invalid_file_type' => 'نوع الملف غير مدعوم. الأنواع المدعومة: :allowed_types',
        'upload_failed' => 'فشل في رفع الملف. حاول مرة أخرى',
        'file_corrupted' => 'الملف تالف أو غير صحيح',
        'unauthorized' => 'غير مصرح لك برفع الملفات',
        'server_error' => 'خطأ في الخادم. حاول مرة أخرى لاحقاً',
    ],

    // إعدادات خاصة بكل نوع ملف
    'file_type_configs' => [
        'slider_images' => [
            'directory' => 'sliders',
            'max_width' => 1920,
            'max_height' => 1080,
            'quality' => 90,
            'required_dimensions' => false,
        ],
        'profile_photos' => [
            'directory' => 'profile_photos',
            'max_width' => 800,
            'max_height' => 800,
            'quality' => 85,
            'required_dimensions' => false,
        ],
        'general_uploads' => [
            'directory' => 'uploads',
            'max_width' => 1920,
            'max_height' => 1080,
            'quality' => 85,
            'required_dimensions' => false,
        ],
    ],

    // إعدادات متقدمة
    'advanced' => [
        'use_cloud_storage' => false,
        'cloud_storage_disk' => 's3',
        'generate_unique_names' => true,
        'preserve_original_names' => false,
        'auto_orient_images' => true,
        'strip_metadata' => true,
    ],
];
