<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomizeFeature extends Model
{
    protected $fillable = [
        'category_id',
        'key',
        'label',
        'input_type',
        'unit',
        'min',
        'max',
        'step',
        'options_json',
        'is_required',
    ];

    protected $casts = [
        'options_json' => 'array',
        'is_required'  => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function prices()
    {
        return $this->hasMany(CustomizeFeaturePrice::class);
    }
}
