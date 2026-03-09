<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'tran_id',
        'amount',
        'currency',
        'status',
        'val_id',
        'gateway_response',
        'user_id',
        'category_id',
        'product_id',
        'location_id',
        'billing_cycle_id',
        'product_name',
        'config',
        'line_items',
        'meta',
        'paid_at',
        'whmcs_client_id',
        'whmcs_order_id',
        'whmcs_invoice_id',
        'whmcs_service_id',
        'whmcs_status',
    ];

    protected $casts = [
        'config'      => 'array',
        'line_items'  => 'array',
        'meta'        => 'array',
        'paid_at'     => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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
