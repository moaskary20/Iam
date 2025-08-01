<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Testing Login Issues...\n\n";

// Get first user
$user = App\Models\User::first();
if ($user) {
    echo "✅ Test user found: {$user->email}\n";
    echo "   Name: {$user->name}\n";
    echo "   Created: {$user->created_at}\n";
} else {
    echo "❌ No users found\n";
    exit;
}

// Test routes
echo "\n📍 Checking Routes:\n";
$routes = collect(app('router')->getRoutes()->getRoutes());

$adminRoutes = $routes->filter(function ($route) {
    return str_starts_with($route->uri(), 'admin');
});

foreach ($adminRoutes as $route) {
    $methods = implode('|', $route->methods());
    echo "   {$methods} {$route->uri()}\n";
}

// Test CSRF
echo "\n🔐 CSRF Configuration:\n";
$csrfMiddleware = app(\App\Http\Middleware\VerifyCsrfToken::class);
$reflection = new ReflectionClass($csrfMiddleware);
$exceptProperty = $reflection->getProperty('except');
$exceptProperty->setAccessible(true);
$except = $exceptProperty->getValue($csrfMiddleware);

if (empty($except)) {
    echo "   ✅ CSRF protection enabled (no exceptions)\n";
} else {
    echo "   ⚠️  CSRF exceptions: " . implode(', ', $except) . "\n";
}

// Test session config
echo "\n💾 Session Configuration:\n";
echo "   Driver: " . config('session.driver') . "\n";
echo "   Lifetime: " . config('session.lifetime') . " minutes\n";

echo "\n🎯 Test at: http://127.0.0.1:8000/admin\n";
echo "   Email: {$user->email}\n";
echo "   Try password: password or password123\n";
