<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type','name','display_name','country_id','tax_id','billing_address','status',
        'email','phone','website','address','is_active',
    ];

    protected $casts = [
        'billing_address' => 'array',
        'is_active'       => 'boolean',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    // Primary (main) contact for fallback display
    public function primaryContact()
    {
        return $this->hasOne(Contact::class)->where('is_primary', true);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
