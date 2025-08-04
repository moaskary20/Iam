<?php
// اختبار PayPal مع Laravel
require_once __DIR__ . '/vendor/autoload.php';

// بدء Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';

// يجب عمل bootstrap للتطبيق
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();

try {
    // تجهيز Laravel
    $kernel->bootstrap();
    
    echo "<h1>Laravel PayPal Test</h1>";
    
    // اختبار قراءة متغيرات البيئة
    echo "<h2>Environment Variables:</h2>";
    echo "PAYPAL_CLIENT_ID: " . (env('PAYPAL_CLIENT_ID') ? 'SET (' . substr(env('PAYPAL_CLIENT_ID'), 0, 15) . '...)' : 'NOT SET') . "<br>";
    echo "PAYPAL_CLIENT_SECRET: " . (env('PAYPAL_CLIENT_SECRET') ? 'SET' : 'NOT SET') . "<br>";
    echo "PAYPAL_MODE: " . (env('PAYPAL_MODE') ?: 'NOT SET') . "<br>";
    
    // اختبار PayPalService
    echo "<h2>PayPal Service Test:</h2>";
    
    try {
        $paypalService = new App\Services\PayPalService();
        echo "✅ PayPalService created successfully<br>";
        
        try {
            $token = $paypalService->getAccessToken();
            echo "✅ Access token obtained: " . substr($token, 0, 30) . "...<br>";
            
            // اختبار إنشاء payment
            echo "<h2>Create Payment Test:</h2>";
            $payment = $paypalService->createPayment(
                10.00,
                'Test Payment',
                'http://localhost:8000/success',
                'http://localhost:8000/cancel'
            );
            
            if (isset($payment['links'])) {
                foreach ($payment['links'] as $link) {
                    if ($link['rel'] === 'approval_url') {
                        echo "✅ Payment created successfully<br>";
                        echo "Payment ID: " . $payment['id'] . "<br>";
                        echo "Approval URL: <a href='" . $link['href'] . "' target='_blank'>Click to pay</a><br>";
                        break;
                    }
                }
            } else {
                echo "❌ No approval URL in payment response<br>";
                echo "Response: " . json_encode($payment) . "<br>";
            }
            
        } catch (Exception $e) {
            echo "❌ Access token error: " . $e->getMessage() . "<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ PayPalService error: " . $e->getMessage() . "<br>";
    }
    
    // اختبار PayPalController
    echo "<h2>PayPal Controller Test:</h2>";
    
    try {
        $controller = new App\Http\Controllers\PayPalController();
        echo "✅ PayPalController created successfully<br>";
        
        // محاكاة request
        $testRequest = new Illuminate\Http\Request();
        $testRequest->merge([
            'amount' => 10.00,
            'description' => 'Test payment'
        ]);
        
        $response = $controller->createPayment($testRequest);
        $responseData = $response->getContent();
        $decoded = json_decode($responseData, true);
        
        if (isset($decoded['success']) && $decoded['success']) {
            echo "✅ Controller test successful<br>";
            echo "Approval URL: " . $decoded['approval_url'] . "<br>";
        } else {
            echo "❌ Controller test failed<br>";
            echo "Response: " . $responseData . "<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ PayPalController error: " . $e->getMessage() . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Laravel bootstrap error: " . $e->getMessage() . "<br>";
}
?>
