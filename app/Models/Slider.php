<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'image', 'link', 'order', 'active'
    ];

    // Always prefix image path with 'sliders/' if not already present
    public function setImageAttribute($value)
    {
        if ($value && !str_starts_with($value, 'sliders/')) {
            $this->attributes['image'] = 'sliders/' . ltrim($value, '/');
        } else {
            $this->attributes['image'] = $value;
        }
    }
}
