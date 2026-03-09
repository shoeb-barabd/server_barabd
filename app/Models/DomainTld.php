<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainTld extends Model
{
    protected $fillable = [
        'tld',
        'register_price',
        'renew_price',
        'transfer_price',
        'currency',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'register_price'  => 'decimal:2',
            'renew_price'     => 'decimal:2',
            'transfer_price'  => 'decimal:2',
            'is_active'       => 'boolean',
        ];
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
