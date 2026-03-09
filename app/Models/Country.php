<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name','iso2','iso3','phone_code','is_active'];

    public function taxRules() { return $this->hasMany(TaxRule::class); }
    public function accounts() { return $this->hasMany(Account::class); }
}
