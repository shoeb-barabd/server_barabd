<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AddOnPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'add_on_id', 'location_id', 'billing_cycle_id', 'unit_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public function addOn()
    {
        return $this->belongsTo(AddOn::class);
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
