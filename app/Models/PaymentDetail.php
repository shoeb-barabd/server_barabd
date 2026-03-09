<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'location_id',
        'billing_cycle_id',
        'plan_title',
        'amount',
        'currency',
        'first_name',
        'last_name',
        'email',
        'phone',
        'phone_country_code',
        'phone_full',
        'street_address',
        'street_address_2',
        'city',
        'state',
        'postal_code',
        'domain_name',
        'tld',
        'payment_method',
        'notes',
        'status',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
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
