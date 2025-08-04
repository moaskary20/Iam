<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار PayPal</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .status {
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .form-group {
            margin: 15px 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .test-result {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>اختبار PayPal مع Laravel</h1>
        
        <?php
        // تحميل Laravel
        require_once __DIR__ . '/vendor/autoload.php';
        
        $app = require_once __DIR__ . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        
        try {
            // تشغيل Laravel
            $request = Illuminate\Http\Request::capture();
            $response = $kernel->handle($request);
            
            echo '<div class="status success">✅ Laravel تم تحميله بنجاح</div>';
            
            // اختبار قراءة متغيرات البيئة
            $paypalMode = env('PAYPAL_MODE');
            $paypalClientId = env('PAYPAL_CLIENT_ID');
            $paypalClientSecret = env('PAYPAL_CLIENT_SECRET');
            
            echo '<div class="test-result">';
            echo '<h3>متغيرات البيئة:</h3>';
            echo '<p><strong>PAYPAL_MODE:</strong> ' . ($paypalMode ?: 'غير محدد') . '</p>';
            echo '<p><strong>PAYPAL_CLIENT_ID:</strong> ' . ($paypalClientId ? substr($paypalClientId, 0, 10) . '...' : 'غير محدد') . '</p>';
            echo '<p><strong>PAYPAL_CLIENT_SECRET:</strong> ' . ($paypalClientSecret ? 'محدد (مخفي)' : 'غير محدد') . '</p>';
            echo '</div>';
            
            // اختبار إنشاء PayPalService
            if ($paypalClientId && $paypalClientSecret) {
                echo '<div class="test-result">';
                echo '<h3>اختبار PayPalService:</h3>';
                
                try {
                    $paypalService = new App\Services\PayPalService();
                    echo '<div class="status success">✅ تم إنشاء PayPalService بنجاح</div>';
                    
                    // اختبار الحصول على access token
                    try {
                        $accessToken = $paypalService->getAccessToken();
                        echo '<div class="status success">✅ تم الحصول على Access Token بنجاح</div>';
                        echo '<p>Token Preview: ' . substr($accessToken, 0, 20) . '...</p>';
                    } catch (Exception $e) {
                        echo '<div class="status error">❌ فشل في الحصول على Access Token: ' . $e->getMessage() . '</div>';
                    }
                    
                } catch (Exception $e) {
                    echo '<div class="status error">❌ فشل في إنشاء PayPalService: ' . $e->getMessage() . '</div>';
                }
                echo '</div>';
            } else {
                echo '<div class="status error">❌ متغيرات PayPal غير محددة</div>';
            }
            
        } catch (Exception $e) {
            echo '<div class="status error">❌ خطأ في تحميل Laravel: ' . $e->getMessage() . '</div>';
        }
        ?>
        
        <div class="test-result">
            <h3>اختبار دفع PayPal:</h3>
            <form method="post" action="">
                <div class="form-group">
                    <label for="amount">المبلغ ($):</label>
                    <input type="number" id="amount" name="amount" value="10.00" step="0.01" min="1" required>
                </div>
                
                <div class="form-group">
                    <label for="description">الوصف:</label>
                    <input type="text" id="description" name="description" value="اختبار دفع PayPal" required>
                </div>
                
                <button type="submit" name="test_payment">اختبار إنشاء دفعة PayPal</button>
            </form>
            
            <?php
            if (isset($_POST['test_payment'])) {
                $amount = $_POST['amount'];
                $description = $_POST['description'];
                
                try {
                    if (isset($paypalService)) {
                        $returnUrl = 'http://localhost:8000/test_success';
                        $cancelUrl = 'http://localhost:8000/test_cancel';
                        
                        $payment = $paypalService->createPayment($amount, $description, $returnUrl, $cancelUrl);
                        
                        if (isset($payment['links'])) {
                            foreach ($payment['links'] as $link) {
                                if ($link['rel'] === 'approval_url') {
                                    echo '<div class="status success">✅ تم إنشاء دفعة PayPal بنجاح!</div>';
                                    echo '<p><strong>رابط الدفع:</strong> <a href="' . $link['href'] . '" target="_blank">اضغط هنا للدفع</a></p>';
                                    echo '<p><strong>Payment ID:</strong> ' . $payment['id'] . '</p>';
                                    break;
                                }
                            }
                        } else {
                            echo '<div class="status error">❌ لم يتم إرجاع رابط الدفع</div>';
                            echo '<pre>' . json_encode($payment, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                        }
                    } else {
                        echo '<div class="status error">❌ PayPal Service غير متاح</div>';
                    }
                } catch (Exception $e) {
                    echo '<div class="status error">❌ خطأ في إنشاء الدفعة: ' . $e->getMessage() . '</div>';
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
