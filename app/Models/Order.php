<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_number',
        'sell_method',
        'product_price',
        'marketing_fee',
        'system_commission',
        'total_cost',
        'status',
        'shipping_details',
        'share_link',
        'referrer_id',
        'notes'
    ];

    protected $casts = [
        'shipping_details' => 'array',
        'product_price' => 'decimal:2',
        'marketing_fee' => 'decimal:2',
        'system_commission' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function generateOrderNumber()
    {
        $prefix = 'ORD-';
        $timestamp = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        return $prefix . $timestamp . '-' . $random;
    }

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            $order->order_number = $order->generateOrderNumber();
        });
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">⏳ في الانتظار</span>',
            'processing' => '<span class="badge bg-info">🔄 قيد المعالجة</span>',
            'shipped' => '<span class="badge bg-primary">🚚 تم الشحن</span>',
            'delivered' => '<span class="badge bg-success">✅ تم التسليم</span>',
            'cancelled' => '<span class="badge bg-danger">❌ ملغي</span>',
            default => '<span class="badge bg-secondary">غير محدد</span>'
        };
    }

    public function getSellMethodBadgeAttribute()
    {
        return match($this->sell_method) {
            'shipping' => '<span class="badge bg-primary">🚚 شحن منزلي</span>',
            'ai' => '<span class="badge bg-info">🤖 ذكاء اصطناعي</span>',
            'social' => '<span class="badge bg-success">📱 سوشيال ميديا</span>',
            default => '<span class="badge bg-secondary">غير محدد</span>'
        };
    }
}
