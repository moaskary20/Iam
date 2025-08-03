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
        $paypalMethod = PaymentMethod::where('name', 'باي بال')->first();
        
        if (!$paypalMethod || !$paypalMethod->active) {
            throw new Exception('PayPal payment method is not configured or active');
        }

        $config = $paypalMethod->config;
        $this->clientId = $config['client_id'] ?? env('PAYPAL_CLIENT_ID');
        $this->clientSecret = $config['client_secret'] ?? env('PAYPAL_CLIENT_SECRET');
        $this->mode = $config['mode'] ?? env('PAYPAL_MODE', 'sandbox');
        $this->currency = $config['currency'] ?? 'USD';
        
        $this->apiUrl = $this->mode === 'sandbox' 
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
    }

    public function getAccessToken()
    {
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

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        
        if (isset($data['access_token'])) {
            return $data['access_token'];
        }
        
        throw new Exception('Unable to get PayPal access token');
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
