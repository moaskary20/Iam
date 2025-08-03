<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'paypal_email',
        'phone',
        'full_name',
        'status',
        'admin_notes',
        'processed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">⏳ في الانتظار</span>',
            'processing' => '<span class="badge bg-info">🔄 قيد المعالجة</span>',
            'completed' => '<span class="badge bg-success">✅ مكتمل</span>',
            'rejected' => '<span class="badge bg-danger">❌ مرفوض</span>',
            default => '<span class="badge bg-secondary">غير محدد</span>'
        };
    }
}
