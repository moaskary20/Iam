<?php

use Illuminate\Support\Str;

return [
    'driver' => env('SESSION_DRIVER', 'database'),
    'lifetime' => (int) env('SESSION_LIFETIME', 120),
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),
    'encrypt' => env('SESSION_ENCRYPT', false),
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => env('SESSION_TABLE', 'sessions'),
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env('SESSION_COOKIE', Str::snake((string) env('APP_NAME', 'laravel')).'_session'),
    'path' => env('SESSION_PATH', '/'),
    'domain' => '.iam-shop.online',
    'secure' => true,
    'http_only' => true,
    'same_site' => 'none',
    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

// ...باقي التعليقات الافتراضية من Laravel...
];
