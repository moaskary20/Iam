<?php

namespace App\Services;

use Exception;

class SimplePayPalService
{
    private $clientId;
    private $clientSecret;
    private $mode;
    private $apiUrl;

    public function __construct()
    {
        // قراءة مباشرة من ملف .env
        $envFile = base_path('.env');
        $envData = [];
        
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && !str_starts_with($line, '#')) {
                    list($key, $value) = explode('=', $line, 2);
                    $envData[trim($key)] = trim($value);
                }
            }
        }
        
        $this->clientId = $envData['PAYPAL_CLIENT_ID'] ?? null;
        $this->clientSecret = $envData['PAYPAL_CLIENT_SECRET'] ?? null;
        $this->mode = $envData['PAYPAL_MODE'] ?? 'sandbox';
        
        if (!$this->clientId || !$this->clientSecret) {
            throw new Exception('PayPal credentials not found in .env file');
        }
        
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new Exception('cURL Error: ' . $curlError);
        }

        $data = json_decode($response, true);
        
        if (isset($data['access_token'])) {
            return $data['access_token'];
        }
        
        throw new Exception('Unable to get PayPal access token. Response: ' . $response);
    }
}
