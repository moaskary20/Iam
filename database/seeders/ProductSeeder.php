<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Market;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $markets = Market::all();
        
        foreach ($markets as $market) {
            // إضافة منتجات تجريبية لكل سوق
            for ($i = 1; $i <= 3; $i++) {
                Product::create([
                    'market_id' => $market->id,
                    'name' => "منتج {$i} - {$market->name}",
                    'description' => "وصف تفصيلي للمنتج {$i} في {$market->name}. يحتوي هذا المنتج على مميزات عديدة ومفيدة للعملاء.",
                    'images' => [], // سيتم إضافة الصور لاحقاً من الأدمن بانل
                    'purchase_price' => rand(100, 1000),
                    'expected_selling_price' => rand(100, 1000) * 1.15,
                    'system_commission' => 5.00,
                    'marketing_commission' => 3.00,
                    'active' => true,
                    'order' => $i,
                ]);
            }
        }
    }
}
