<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Preset extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id','name','slug','config','included_addons',
        'sort_order','is_featured'
    ];

    protected $casts = [
        'config'          => 'array',
        'included_addons' => 'array',
        'sort_order'      => 'integer',
        'is_featured'     => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
