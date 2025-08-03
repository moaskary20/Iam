<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'بطاقة ائتمان',
                'active' => true,
                'config' => null
            ],
            [
                'name' => 'الدفع عند الاستلام',
                'active' => true,
                'config' => null
            ],
            [
                'name' => 'حوالة بنكية',
                'active' => true,
                'config' => null
            ],
            [
                'name' => 'محفظة إلكترونية',
                'active' => true,
                'config' => null
            ],
            [
                'name' => 'باي بال',
                'active' => true,
                'config' => [
                    'client_id' => env('PAYPAL_CLIENT_ID'),
                    'client_secret' => env('PAYPAL_CLIENT_SECRET'),
                    'mode' => env('PAYPAL_MODE', 'sandbox'),
                    'currency' => 'USD'
                ]
            ]
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(
                ['name' => $method['name']],
                $method
            );
        }
    }
}
