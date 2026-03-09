<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExchangeRate extends Model
{
    use HasFactory;
    protected $fillable = ['base_currency_code','quote_currency_code','rate','valid_from','valid_to'];

    public function base()  { return $this->belongsTo(Currency::class, 'base_currency_code', 'code'); }
    public function quote() { return $this->belongsTo(Currency::class, 'quote_currency_code', 'code'); }
}
