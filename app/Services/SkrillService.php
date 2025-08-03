<?php

namespace App\Services;

use App\Models\PaymentMethod;
use Exception;

class SkrillService
{
    private $merchantId;
    private $secretWord;
    private $merchantEmail;
    private $apiUrl;
    private $currency;

    public function __construct()
    {
        $skrillMethod = PaymentMethod::where('name', 'Skrill')->first();
        
        if (!$skrillMethod || !$skrillMethod->active) {
            throw new Exception('Skrill payment method is not configured or active');
        }

        $config = $skrillMethod->config;
        $this->merchantId = $config['merchant_id'] ?? env('SKRILL_MERCHANT_ID');
        $this->secretWord = $config['secret_word'] ?? env('SKRILL_SECRET_WORD');
        $this->merchantEmail = $config['merchant_email'] ?? env('SKRILL_MERCHANT_EMAIL');
        $this->currency = $config['currency'] ?? 'USD';
        
        $this->apiUrl = 'https://www.moneybookers.com/app/payment.pl';
    }

    public function createPayment($amount, $description, $returnUrl, $cancelUrl, $orderId = null)
    {
        $orderId = $orderId ?? uniqid('order_');
        
        $paymentData = [
            'pay_to_email' => $this->merchantEmail,
            'recipient_description' => 'IAM Payment',
            'transaction_id' => $orderId,
            'return_url' => $returnUrl,
            'cancel_url' => $cancelUrl,
            'status_url' => route('skrill.status'),
            'language' => 'AR',
            'amount' => number_format($amount, 2, '.', ''),
            'currency' => $this->currency,
            'detail1_description' => 'Order Description:',
            'detail1_text' => $description,
            'logo_url' => asset('images/logo.png'), // إضافة لوجو الموقع
        ];

        return [
            'payment_url' => $this->apiUrl,
            'form_data' => $paymentData,
            'transaction_id' => $orderId
        ];
    }

    public function verifyPayment($data)
    {
        // التحقق من صحة الدفع باستخدام MD5 signature
        $concatFields = $data['merchant_id'] . 
                       $data['transaction_id'] . 
                       strtoupper(md5($this->secretWord)) . 
                       $data['mb_amount'] . 
                       $data['mb_currency'] . 
                       $data['status'];

        $expectedSignature = strtoupper(md5($concatFields));
        
        return $expectedSignature === strtoupper($data['md5sig']);
    }

    public function isPaymentSuccessful($status)
    {
        // Status codes: 2 = processed, 0 = pending, -1 = cancelled, -2 = failed, -3 = chargeback
        return $status == '2';
    }
}
