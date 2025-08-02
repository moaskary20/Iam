<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// تحميل Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// إنشاء المستخدم
$user = User::create([
    'name' => 'محمد عسكري',
    'email' => 'mo.askary@gmail.com',
    'password' => Hash::make('newpassword'),
    'email_verified_at' => now(),
    'is_verified' => true,
    'verification_status' => 'verified',
    'current_market_id' => 1,
    'current_product_index' => 0,
    'unlocked_markets' => json_encode([1]),
    'balance' => 100.00,
]);

echo "تم إنشاء المستخدم بنجاح!\n";
echo "الاسم: " . $user->name . "\n";
echo "البريد الإلكتروني: " . $user->email . "\n";
echo "الرصيد: " . $user->balance . " دولار\n";
