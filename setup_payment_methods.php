<?php

// إضافة طرق الدفع مباشرة إلى قاعدة البيانات
require_once __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

// قراءة ملف .env
$envFile = __DIR__ . '/.env';
$envVars = [];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $envVars[trim($key)] = trim($value);
        }
    }
}

// الاتصال بقاعدة البيانات SQLite
$dbPath = __DIR__ . '/database/database.sqlite';

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ تم الاتصال بقاعدة البيانات بنجاح\n";
    
    // تحقق من وجود جدول payment_methods
    $tableExists = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='payment_methods'")->fetch();
    
    if (!$tableExists) {
        echo "❌ جدول payment_methods غير موجود. يجب تشغيل المايجريشن أولاً\n";
        exit(1);
    }
    
    // إضافة أو تحديث PayPal
    $stmt = $pdo->prepare("
        INSERT OR REPLACE INTO payment_methods (id, name, active, config, created_at, updated_at) 
        VALUES (
            (SELECT id FROM payment_methods WHERE name = 'باي بال'),
            'باي بال', 
            1, 
            ?, 
            datetime('now'), 
            datetime('now')
        )
    ");
    
    $paypalConfig = json_encode([
        'client_id' => $envVars['PAYPAL_CLIENT_ID'] ?? '',
        'client_secret' => $envVars['PAYPAL_CLIENT_SECRET'] ?? '',
        'mode' => $envVars['PAYPAL_MODE'] ?? 'sandbox',
        'currency' => 'USD'
    ]);
    
    $stmt->execute([$paypalConfig]);
    echo "✅ تم إعداد PayPal بنجاح\n";
    
    // إضافة Skrill
    $stmt = $pdo->prepare("
        INSERT OR REPLACE INTO payment_methods (id, name, active, config, created_at, updated_at) 
        VALUES (
            (SELECT id FROM payment_methods WHERE name = 'سكريل'),
            'سكريل', 
            1, 
            ?, 
            datetime('now'), 
            datetime('now')
        )
    ");
    
    $skrillConfig = json_encode([
        'merchant_email' => $envVars['SKRILL_MERCHANT_EMAIL'] ?? '',
        'merchant_id' => $envVars['SKRILL_MERCHANT_ID'] ?? '',
        'secret_word' => $envVars['SKRILL_SECRET_WORD'] ?? '',
        'currency' => 'USD'
    ]);
    
    $stmt->execute([$skrillConfig]);
    echo "✅ تم إعداد Skrill بنجاح\n";
    
    // إضافة تحويل بنكي
    $stmt = $pdo->prepare("
        INSERT OR REPLACE INTO payment_methods (id, name, active, config, created_at, updated_at) 
        VALUES (
            (SELECT id FROM payment_methods WHERE name = 'تحويل بنكي'),
            'تحويل بنكي', 
            1, 
            ?, 
            datetime('now'), 
            datetime('now')
        )
    ");
    
    $bankConfig = json_encode([
        'bank_name' => 'البنك الأهلي',
        'account_number' => '1234567890',
        'iban' => 'SA1234567890123456789012',
        'account_name' => 'اسم صاحب الحساب'
    ]);
    
    $stmt->execute([$bankConfig]);
    echo "✅ تم إعداد التحويل البنكي بنجاح\n";
    
    // إضافة كارت ائتمان
    $stmt = $pdo->prepare("
        INSERT OR REPLACE INTO payment_methods (id, name, active, config, created_at, updated_at) 
        VALUES (
            (SELECT id FROM payment_methods WHERE name = 'كارت ائتمان'),
            'كارت ائتمان', 
            1, 
            ?, 
            datetime('now'), 
            datetime('now')
        )
    ");
    
    $cardConfig = json_encode([
        'processor' => 'stripe',
        'currency' => 'USD'
    ]);
    
    $stmt->execute([$cardConfig]);
    echo "✅ تم إعداد كارت الائتمان بنجاح\n";
    
    // عرض طرق الدفع المثبتة
    echo "\n=== طرق الدفع المثبتة ===\n";
    $methods = $pdo->query("SELECT * FROM payment_methods")->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($methods as $method) {
        echo "- " . $method['name'] . " (" . ($method['active'] ? 'نشط' : 'غير نشط') . ")\n";
    }
    
    echo "\n✅ تم إعداد جميع طرق الدفع بنجاح!\n";
    
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}
