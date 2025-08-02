<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Market;

class MarketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $markets = [
            [
                'name' => 'السوق الأول',
                'description' => 'السوق الأول للمنتجات المتنوعة',
                'order' => 1,
                'active' => true,
            ],
            [
                'name' => 'السوق الثاني',
                'description' => 'السوق الثاني للمنتجات المتخصصة',
                'order' => 2,
                'active' => true,
            ],
            [
                'name' => 'السوق الثالث',
                'description' => 'السوق الثالث للمنتجات الحديثة',
                'order' => 3,
                'active' => true,
            ],
            [
                'name' => 'السوق الرابع',
                'description' => 'السوق الرابع للمنتجات المميزة',
                'order' => 4,
                'active' => true,
            ],
            [
                'name' => 'السوق المفتوح',
                'description' => 'السوق المفتوح لجميع أنواع المنتجات',
                'order' => 5,
                'active' => true,
            ],
        ];

        foreach ($markets as $market) {
            Market::create($market);
        }
    }
}
