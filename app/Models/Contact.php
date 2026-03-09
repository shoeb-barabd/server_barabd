<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'account_id','first_name','last_name','email','phone',
        'designation','is_primary','notify_flags',
    ];

    protected $casts = [
        'is_primary'   => 'boolean',
        'notify_flags' => 'array',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // Virtual full name
    public function getNameAttribute(): string
    {
        return trim(($this->first_name ?? '').' '.($this->last_name ?? ''));
    }

    // Order helper
    public function scopeOrderByName($q)
    {
        return $q->orderBy('first_name')->orderBy('last_name');
    }
}
