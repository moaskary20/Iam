<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Testing CSRF and Session...\n\n";

// Test Session Driver
echo "💾 Session Configuration:\n";
echo "   Driver: " . config('session.driver') . "\n";
echo "   Lifetime: " . config('session.lifetime') . " minutes\n";
echo "   Encrypt: " . (config('session.encrypt') ? 'Yes' : 'No') . "\n";

// Test Sessions Table
try {
    $sessionCount = DB::table('sessions')->count();
    echo "   Sessions in DB: $sessionCount\n";
} catch (Exception $e) {
    echo "   ❌ Sessions table error: " . $e->getMessage() . "\n";
}

// Test CSRF
echo "\n🔐 CSRF Configuration:\n";
$csrfMiddleware = app(\App\Http\Middleware\VerifyCsrfToken::class);
$reflection = new ReflectionClass($csrfMiddleware);
$exceptProperty = $reflection->getProperty('except');
$exceptProperty->setAccessible(true);
$except = $exceptProperty->getValue($csrfMiddleware);

if (empty($except)) {
    echo "   ⚠️  No CSRF exceptions\n";
} else {
    echo "   ✅ CSRF exceptions: " . implode(', ', $except) . "\n";
}

// Test if we can generate CSRF token
try {
    $token = csrf_token();
    echo "   ✅ CSRF token generated: " . substr($token, 0, 10) . "...\n";
} catch (Exception $e) {
    echo "   ❌ CSRF token error: " . $e->getMessage() . "\n";
}

echo "\n🎯 Now try: http://127.0.0.1:8000/admin\n";
