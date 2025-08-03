<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'active',
        'config'
    ];

    protected $casts = [
        'active' => 'boolean',
        'config' => 'array'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
