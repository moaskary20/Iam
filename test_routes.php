<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Testing Login Routes...\n\n";

// Test route registration
$routes = collect(app('router')->getRoutes()->getRoutes());

$adminLoginGet = $routes->filter(function ($route) {
    return $route->uri() === 'admin/login' && in_array('GET', $route->methods());
})->first();

$adminLoginPost = $routes->filter(function ($route) {
    return $route->uri() === 'admin/login' && in_array('POST', $route->methods());
})->first();

echo $adminLoginGet ? "✅ GET admin/login route exists\n" : "❌ GET admin/login route missing\n";
echo $adminLoginPost ? "✅ POST admin/login route exists\n" : "❌ POST admin/login route missing\n";

// Test user access
$user = App\Models\User::first();
if ($user) {
    echo "✅ Test user: {$user->email}\n";
    echo "✅ User can access panel: " . ($user->canAccessPanel(app('filament')->getDefaultPanel()) ? "Yes" : "No") . "\n";
}

echo "\n🎯 Login should now work at: http://127.0.0.1:8000/admin\n";
