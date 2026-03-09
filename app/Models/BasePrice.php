<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BasePrice extends Model {
    protected $fillable = ['product_id','location_id','billing_cycle_id','amount'];
    public function product(){ return $this->belongsTo(Product::class); }
    public function location(){ return $this->belongsTo(Location::class); }
    public function billingCycle(){ return $this->belongsTo(BillingCycle::class); }
}
