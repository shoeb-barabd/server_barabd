<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FeaturePrice extends Model {
    protected $fillable = ['product_feature_id','location_id','billing_cycle_id','included_value','step','price_per_step'];
    public function feature(){ return $this->belongsTo(ProductFeature::class, 'product_feature_id'); }
    public function location(){ return $this->belongsTo(Location::class); }
    public function billingCycle(){ return $this->belongsTo(BillingCycle::class); }
}
