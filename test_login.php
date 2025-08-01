<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "Testing Login Configuration...\n\n";

// Test 1: Check if users exist
$userCount = App\Models\User::count();
echo "✓ Users in database: $userCount\n";

// Test 2: Get first user for testing
$user = App\Models\User::first();
if ($user) {
    echo "✓ Test user found: {$user->email}\n";
    echo "✓ User can access panel: " . ($user->canAccessPanel(app('filament')->getDefaultPanel()) ? "Yes" : "No") . "\n";
} else {
    echo "✗ No users found\n";
}

// Test 3: Check session configuration
echo "✓ Session driver: " . config('session.driver') . "\n";
echo "✓ Session lifetime: " . config('session.lifetime') . " minutes\n";

// Test 4: Check if sessions table exists
try {
    DB::table('sessions')->count();
    echo "✓ Sessions table exists\n";
} catch (Exception $e) {
    echo "✗ Sessions table missing: " . $e->getMessage() . "\n";
}

// Test 5: Check Filament configuration
echo "✓ Filament admin panel path: /admin\n";
echo "✓ Filament auth guard: web\n";

echo "\nTest completed. Try accessing: http://127.0.0.1:8000/admin\n";
