<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillingCycle extends Model
{
    use HasFactory;

    protected $fillable = ['key','name','months','is_active','whmcs_cycle'];

    protected $casts = [
        'months'    => 'integer',
        'is_active' => 'boolean',
    ];

    public function addOnPrices()
    {
        return $this->hasMany(AddOnPrice::class);
    }

    public function basePrices()
    {
        return $this->hasMany(BasePrice::class);
    }


    public function featurePrices()
    {
        return $this->hasMany(FeaturePrice::class);
    }

    /** Query Scopes */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


}
