<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'icon_class',
        'save_text',
        'is_active',
        'whmcs_product_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function presets()
    {
        return $this->hasMany(Preset::class);
    }
    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }


    public function basePrices()
    {
        return $this->hasMany(BasePrice::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

}
