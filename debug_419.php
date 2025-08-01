<?php

echo "🔍 Debugging 419 Page Expired Issue...\n\n";

// Test 1: Check if we can create sessions
echo "1️⃣ Testing Session Creation:\n";
try {
    session_start();
    $_SESSION['test'] = 'working';
    if (isset($_SESSION['test'])) {
        echo "   ✅ PHP sessions work\n";
    }
    session_destroy();
} catch (Exception $e) {
    echo "   ❌ PHP session error: " . $e->getMessage() . "\n";
}

// Test 2: Check Laravel session config
echo "\n2️⃣ Checking Laravel Session Config:\n";

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "   Driver: " . config('session.driver') . "\n";
echo "   Lifetime: " . config('session.lifetime') . " minutes\n";
echo "   Path: " . config('session.path') . "\n";
echo "   Domain: " . config('session.domain') . "\n";
echo "   Secure: " . (config('session.secure') ? 'true' : 'false') . "\n";
echo "   HttpOnly: " . (config('session.http_only') ? 'true' : 'false') . "\n";

// Test 3: Check database sessions table
echo "\n3️⃣ Database Sessions Table:\n";
try {
    $count = DB::table('sessions')->count();
    echo "   ✅ Sessions table exists with $count records\n";
    
    // Check table structure
    $columns = collect(DB::select("PRAGMA table_info(sessions)"));
    echo "   Columns: " . $columns->pluck('name')->join(', ') . "\n";
} catch (Exception $e) {
    echo "   ❌ Database error: " . $e->getMessage() . "\n";
}

// Test 4: Check app key
echo "\n4️⃣ Application Key:\n";
$appKey = config('app.key');
if ($appKey) {
    echo "   ✅ App key exists: " . substr($appKey, 0, 20) . "...\n";
} else {
    echo "   ❌ No app key set!\n";
}

// Test 5: CSRF configuration
echo "\n5️⃣ CSRF Configuration:\n";
$except = app(\App\Http\Middleware\VerifyCsrfToken::class);
$reflection = new ReflectionClass($except);
$property = $reflection->getProperty('except');
$property->setAccessible(true);
$exceptions = $property->getValue($except);
echo "   CSRF exceptions: " . implode(', ', $exceptions) . "\n";

echo "\n🎯 If all above look good, try: http://127.0.0.1:8001/simple-login\n";
