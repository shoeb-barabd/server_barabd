<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
  'name','first_name','last_name','email','password',
  'google_id','avatar','email_verified_at','role','is_active','status'
];


    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Convenience accessor: $user->name
    public function getNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Hash password only if it's not already a bcrypt hash.
     * This avoids double-hashing when values come pre-hashed.
     */
    public function setPasswordAttribute($value): void
    {
        if (! $value) return;

        $this->attributes['password'] =
            str_starts_with((string) $value, '$2y$') ? $value : bcrypt($value);
    }
}
