<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomizeFeaturePrice extends Model
{
    protected $fillable = [
        'customize_feature_id',
        'location_id',
        'billing_cycle_id',
        'included_value',
        'step',
        'price_per_step',
    ];

    public function feature()
    {
        return $this->belongsTo(CustomizeFeature::class, 'customize_feature_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function billingCycle()
    {
        return $this->belongsTo(BillingCycle::class);
    }
}
