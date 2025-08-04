<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodsSeeder extends Seeder
{
    public function run()
    {
        // PayPal
        PaymentMethod::updateOrCreate(
            ['name' => 'باي بال'],
            [
                'active' => true,
                'config' => [
                    'client_id' => env('PAYPAL_CLIENT_ID'),
                    'client_secret' => env('PAYPAL_CLIENT_SECRET'),
                    'mode' => env('PAYPAL_MODE', 'sandbox'),
                    'currency' => 'USD'
                ]
            ]
        );

        // Skrill
        PaymentMethod::updateOrCreate(
            ['name' => 'سكريل'],
            [
                'active' => true,
                'config' => [
                    'merchant_email' => env('SKRILL_MERCHANT_EMAIL'),
                    'merchant_id' => env('SKRILL_MERCHANT_ID'),
                    'secret_word' => env('SKRILL_SECRET_WORD'),
                    'currency' => 'USD'
                ]
            ]
        );

        // Bank Transfer
        PaymentMethod::updateOrCreate(
            ['name' => 'تحويل بنكي'],
            [
                'active' => true,
                'config' => [
                    'bank_name' => 'البنك الأهلي',
                    'account_number' => '1234567890',
                    'iban' => 'SA1234567890123456789012',
                    'account_name' => 'اسم صاحب الحساب'
                ]
            ]
        );

        // Credit Card
        PaymentMethod::updateOrCreate(
            ['name' => 'كارت ائتمان'],
            [
                'active' => true,
                'config' => [
                    'processor' => 'stripe', // أو أي معالج آخر
                    'currency' => 'USD'
                ]
            ]
        );

        echo "✅ تم إنشاء طرق الدفع بنجاح!\n";
    }
}
