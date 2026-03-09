<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainOrder extends Model
{
    protected $fillable = [
        'user_id',
        'domain_name',
        'sld',
        'tld',
        'action',
        'years',
        'amount',
        'currency',
        'status',
        'tran_id',
        'payment_id',
        'op_domain_id',
        'op_status',
        'registration_date',
        'expiry_date',
        'nameservers',
        'registrant',
        'linked_hosting_payment_id',
    ];

    protected function casts(): array
    {
        return [
            'amount'            => 'decimal:2',
            'nameservers'       => 'array',
            'registrant'        => 'array',
            'registration_date' => 'date',
            'expiry_date'       => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
