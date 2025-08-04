<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فحص PayPal</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f0f2f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .status { padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid; }
        .success { background: #d4edda; color: #155724; border-color: #28a745; }
        .error { background: #f8d7da; color: #721c24; border-color: #dc3545; }
        .warning { background: #fff3cd; color: #856404; border-color: #ffc107; }
        .info { background: #d1ecf1; color: #0c5460; border-color: #17a2b8; }
        .debug { background: #f8f9fa; color: #495057; border-color: #6c757d; font-family: monospace; font-size: 12px; }
        h1 { color: #333; }
        .test-btn { background: #007bff; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .test-btn:hover { background: #0056b3; }
        .test-btn:disabled { background: #6c757d; cursor: not-allowed; }
        .loading { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 فحص إعدادات PayPal</h1>
        
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        echo '<div class="info">📁 مسار المجلد الحالي: ' . __DIR__ . '</div>';
        
        $envFile = dirname(__DIR__) . '/.env'; // العودة مجلد واحد للخلف للوصول للمجلد الرئيسي
        echo '<div class="info">📄 مسار ملف .env: ' . $envFile . '</div>';
        
        if (!file_exists($envFile)) {
            echo '<div class="error">❌ ملف .env غير موجود!</div>';
            exit;
        }
        
        echo '<div class="success">✅ ملف .env موجود</div>';
        echo '<div class="info">📊 حجم الملف: ' . filesize($envFile) . ' bytes</div>';
        
        $content = file_get_contents($envFile);
        if (!$content) {
            echo '<div class="error">❌ لا يمكن قراءة ملف .env</div>';
            exit;
        }
        
        $lines = explode("\n", $content);
        echo '<div class="info">📄 عدد الأسطر: ' . count($lines) . '</div>';
        
        // البحث عن أسطر PayPal
        $paypalLines = [];
        foreach ($lines as $lineNum => $line) {
            if (stripos($line, 'PAYPAL') !== false) {
                $paypalLines[] = ['num' => $lineNum + 1, 'content' => trim($line)];
            }
        }
        
        if (empty($paypalLines)) {
            echo '<div class="error">❌ لم يتم العثور على أي إعدادات PayPal في ملف .env</div>';
        } else {
            echo '<div class="success">✅ تم العثور على ' . count($paypalLines) . ' إعدادات PayPal:</div>';
            echo '<div class="debug">';
            foreach ($paypalLines as $line) {
                echo 'السطر ' . $line['num'] . ': ' . htmlspecialchars($line['content']) . '<br>';
            }
            echo '</div>';
        }
        
        // استخراج القيم
        $clientId = '';
        $clientSecret = '';
        $mode = 'sandbox';
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (strpos($line, 'PAYPAL_CLIENT_ID=') === 0) {
                $clientId = trim(substr($line, 17));
            } elseif (strpos($line, 'PAYPAL_CLIENT_SECRET=') === 0) {
                $clientSecret = trim(substr($line, 21));
            } elseif (strpos($line, 'PAYPAL_MODE=') === 0) {
                $mode = trim(substr($line, 12));
            }
        }
        
        echo '<h2>📋 نتائج التحليل:</h2>';
        
        if (empty($clientId)) {
            echo '<div class="error">❌ PAYPAL_CLIENT_ID غير موجود أو فارغ</div>';
        } else {
            echo '<div class="success">✅ PAYPAL_CLIENT_ID موجود: ' . substr($clientId, 0, 20) . '...</div>';
        }
        
        if (empty($clientSecret)) {
            echo '<div class="error">❌ PAYPAL_CLIENT_SECRET غير موجود أو فارغ</div>';
        } else {
            echo '<div class="success">✅ PAYPAL_CLIENT_SECRET موجود: ' . substr($clientSecret, 0, 20) . '...</div>';
        }
        
        echo '<div class="info">🔧 PAYPAL_MODE: ' . ($mode ?: 'sandbox (افتراضي)') . '</div>';
        
        // اختبار الاتصال إذا كانت البيانات موجودة
        if (!empty($clientId) && !empty($clientSecret)) {
            if (isset($_POST['test_connection'])) {
                echo '<h2>🌐 اختبار الاتصال مع PayPal:</h2>';
                
                $apiUrl = ($mode === 'sandbox') ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';
                echo '<div class="info">🔗 API URL: ' . $apiUrl . '</div>';
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiUrl . '/v1/oauth2/token');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_USERPWD, $clientId . ':' . $clientSecret);
                curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Accept: application/json',
                    'Accept-Language: en_US',
                ]);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                
                $startTime = microtime(true);
                $response = curl_exec($ch);
                $endTime = microtime(true);
                $duration = round(($endTime - $startTime) * 1000);
                
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $error = curl_error($ch);
                curl_close($ch);
                
                echo '<div class="info">⏱️ مدة الاستجابة: ' . $duration . ' ms</div>';
                echo '<div class="info">📊 HTTP Code: ' . $httpCode . '</div>';
                
                if ($error) {
                    echo '<div class="error">❌ خطأ في cURL: ' . htmlspecialchars($error) . '</div>';
                } else {
                    $data = json_decode($response, true);
                    
                    if (isset($data['access_token'])) {
                        echo '<div class="success">🎉 نجح الاتصال! تم الحصول على Access Token</div>';
                        echo '<div class="info">⏰ التوكن ينتهي خلال: ' . ($data['expires_in'] ?? 'غير محدد') . ' ثانية</div>';
                        echo '<div class="info">🔑 نوع التوكن: ' . ($data['token_type'] ?? 'غير محدد') . '</div>';
                    } else {
                        echo '<div class="error">❌ فشل في الحصول على Access Token</div>';
                        echo '<div class="debug">Response: ' . htmlspecialchars($response) . '</div>';
                        
                        if (isset($data['error'])) {
                            echo '<div class="error">🚫 Error: ' . htmlspecialchars($data['error']) . '</div>';
                            if (isset($data['error_description'])) {
                                echo '<div class="error">📝 Description: ' . htmlspecialchars($data['error_description']) . '</div>';
                            }
                        }
                        
                        echo '<div class="warning">';
                        echo '<strong>💡 حلول مقترحة:</strong><br>';
                        echo '• تحقق من صحة Client ID و Client Secret في PayPal Developer Console<br>';
                        echo '• تأكد من أن التطبيق نشط ومعتمد<br>';
                        echo '• تحقق من إعدادات الشبكة والـ Firewall<br>';
                        echo '• جرب التبديل بين sandbox و live';
                        echo '</div>';
                    }
                }
            } else {
                echo '<form method="post" style="margin-top: 20px;">';
                echo '<button type="submit" name="test_connection" class="test-btn">🧪 اختبار الاتصال مع PayPal</button>';
                echo '</form>';
            }
        } else {
            echo '<div class="warning">⚠️ لا يمكن اختبار الاتصال بسبب نقص البيانات</div>';
            echo '<div class="info">';
            echo '<strong>📝 للإصلاح، أضف هذه الأسطر إلى ملف .env:</strong><br><br>';
            echo '<code style="background: #f8f9fa; padding: 10px; display: block; border-radius: 5px;">';
            echo 'PAYPAL_CLIENT_ID=your_client_id_here<br>';
            echo 'PAYPAL_CLIENT_SECRET=your_client_secret_here<br>';
            echo 'PAYPAL_MODE=sandbox';
            echo '</code>';
            echo '</div>';
        }
        ?>
        
        <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
            <h3>📚 معلومات إضافية:</h3>
            <p><strong>PayPal Developer Console:</strong> <a href="https://developer.paypal.com/" target="_blank">https://developer.paypal.com/</a></p>
            <p><strong>Sandbox Mode:</strong> للاختبار فقط، لا يتم خصم أموال حقيقية</p>
            <p><strong>Live Mode:</strong> للاستخدام الفعلي مع أموال حقيقية</p>
        </div>
    </div>
</body>
</html>
