<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Debugging Filament Dashboard...\n\n";

// Test 1: Check if user is authenticated
if (auth()->check()) {
    echo "✅ User is authenticated: " . auth()->user()->email . "\n";
} else {
    echo "❌ No authenticated user\n";
}

// Test 2: Check Models
try {
    $users = App\Models\User::count();
    $wallets = App\Models\Wallet::count();
    $sliders = App\Models\Slider::count();
    echo "✅ Models working - Users: $users, Wallets: $wallets, Sliders: $sliders\n";
} catch (Exception $e) {
    echo "❌ Model error: " . $e->getMessage() . "\n";
}

// Test 3: Check Views
if (view()->exists('filament.pages.simple-dashboard')) {
    echo "✅ Dashboard view exists\n";
} else {
    echo "❌ Dashboard view missing\n";
}

// Test 4: Check Filament Panel
try {
    $panel = app('filament')->getDefaultPanel();
    echo "✅ Filament panel: " . $panel->getId() . "\n";
} catch (Exception $e) {
    echo "❌ Filament panel error: " . $e->getMessage() . "\n";
}

echo "\n🎯 Check: http://127.0.0.1:8000/admin\n";
