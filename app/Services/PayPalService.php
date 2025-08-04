<?php

namespace App\Services;

use App\Models\PaymentMethod;
use Exception;

class PayPalService
{
    private $clientId;
    private $clientSecret;
    private $mode;
    private $apiUrl;
    private $currency;

    public function __construct()
    {
        // استخدام متغيرات البيئة مباشرة
        $this->clientId = env('PAYPAL_CLIENT_ID');
        $this->clientSecret = env('PAYPAL_CLIENT_SECRET');
        $this->mode = env('PAYPAL_MODE', 'sandbox');
        $this->currency = 'USD';
        
        // إضافة تشخيص مفصل
        \Log::info('PayPal Service Initialization', [
            'client_id_exists' => !empty($this->clientId),
            'client_id_preview' => $this->clientId ? substr($this->clientId, 0, 10) . '...' : 'NOT SET',
            'client_secret_exists' => !empty($this->clientSecret),
            'mode' => $this->mode
        ]);
        
        // التحقق من وجود البيانات المطلوبة
        if (!$this->clientId || !$this->clientSecret) {
            $errorMsg = 'PayPal credentials missing: ';
            $errorMsg .= !$this->clientId ? 'CLIENT_ID ' : '';
            $errorMsg .= !$this->clientSecret ? 'CLIENT_SECRET ' : '';
            $errorMsg .= 'Check your .env file';
            
            \Log::error($errorMsg);
            throw new Exception($errorMsg);
        }
        
        $this->apiUrl = $this->mode === 'sandbox' 
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
            
        \Log::info('PayPal API URL: ' . $this->apiUrl);
    }

    public function getAccessToken()
    {
        // إضافة تشخيص إضافي
        \Log::info('PayPal Configuration:', [
            'client_id' => substr($this->clientId, 0, 10) . '...',
            'mode' => $this->mode,
            'api_url' => $this->apiUrl
        ]);
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->clientId . ':' . $this->clientSecret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Accept-Language: en_US',
        ]);
        
        // إضافة timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        \Log::info('PayPal API Response:', [
            'http_code' => $httpCode,
            'curl_error' => $curlError,
            'response' => $response
        ]);

        if ($curlError) {
            throw new Exception('cURL Error: ' . $curlError);
        }

        $data = json_decode($response, true);
        
        if (isset($data['access_token'])) {
            return $data['access_token'];
        }
        
        $errorMessage = 'Unable to get PayPal access token';
        if (isset($data['error'])) {
            $errorMessage .= ': ' . $data['error'];
            if (isset($data['error_description'])) {
                $errorMessage .= ' - ' . $data['error_description'];
            }
        }
        
        throw new Exception($errorMessage);
    }

    public function createPayment($amount, $description, $returnUrl, $cancelUrl)
    {
        $accessToken = $this->getAccessToken();
        
        $payment = [
            'intent' => 'sale',
            'payer' => [
                'payment_method' => 'paypal'
            ],
            'transactions' => [[
                'amount' => [
                    'total' => number_format($amount, 2, '.', ''),
                    'currency' => $this->currency
                ],
                'description' => $description
            ]],
            'redirect_urls' => [
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl
            ]
        ];

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/v1/payments/payment');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payment));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function executePayment($paymentId, $payerId)
    {
        $accessToken = $this->getAccessToken();
        
        $execution = [
            'payer_id' => $payerId
        ];

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/v1/payments/payment/' . $paymentId . '/execute');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($execution));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
