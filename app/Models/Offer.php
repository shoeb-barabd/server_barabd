<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'package_name', 'discount_percent', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_percent' => 'float',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
