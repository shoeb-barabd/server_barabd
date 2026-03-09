<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AddOn extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'key', 'label', 'description',
        'unit_type',      // one_time | recurring
        'is_qty_based',   // bool
        'max_qty',        // int
        'is_active',      // bool
    ];

    protected $casts = [
        'is_qty_based' => 'boolean',
        'is_active'    => 'boolean',
    ];

    public function prices()
    {
        return $this->hasMany(AddOnPrice::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
