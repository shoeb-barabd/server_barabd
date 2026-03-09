<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'country_id',
        'currency_code',
        'tax_rate_percent',
        'is_active',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'tax_rate_percent' => 'decimal:2',
    ];

    // app/Models/Location.php
    public function getCurrencySymbolAttribute(): string
    {
        return match($this->currency_code){
            'BDT' => '৳',
            'USD' => '$',
            'EUR' => '€',
            'AED' => '$',
            'SLE' => '$',
            default => $this->currency_code,
        };
    }


    /** Relationships */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function basePrices()
    {
        return $this->hasMany(BasePrice::class);
    }

    public function featurePrices()
    {
        return $this->hasMany(FeaturePrice::class);
    }

    public function addOnPrices()
    {
        return $this->hasMany(AddOnPrice::class);
    }

    /** Scopes */
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
