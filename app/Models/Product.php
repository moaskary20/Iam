<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'market_id',
        'name',
        'description',
        'images',
        'purchase_price',
        'expected_selling_price',
        'system_commission',
        'marketing_commission',
        'active',
        'order'
    ];

    protected $casts = [
        'images' => 'array',
        'purchase_price' => 'decimal:2',
        'expected_selling_price' => 'decimal:2',
        'system_commission' => 'decimal:2',
        'marketing_commission' => 'decimal:2',
        'active' => 'boolean'
    ];

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    // حساب سعر البيع المتوقع تلقائياً
    public function calculateExpectedSellingPrice(): float
    {
        return $this->purchase_price * 1.15; // زيادة 15% كمثال
    }

    // حساب قيمة عمولة النظام
    public function getSystemCommissionAmountAttribute(): float
    {
        return ($this->expected_selling_price * $this->system_commission) / 100;
    }

    // حساب قيمة عمولة التسويق
    public function getMarketingCommissionAmountAttribute(): float
    {
        return ($this->expected_selling_price * $this->marketing_commission) / 100;
    }
}
