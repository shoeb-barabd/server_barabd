<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxRule extends Model
{
    use HasFactory;
    protected $fillable = ['country_id','tax_name','rate_percent','is_inclusive','effective_from','effective_to','notes'];

    public function country() { return $this->belongsTo(Country::class); }
}

