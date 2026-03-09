<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** Relationships */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function customizeFeatures()
    {
        return $this->hasMany(CustomizeFeature::class);
    }
    public function addons()
    {
        return $this->hasMany(AddOn::class);
    }

    /** Query Scopes */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * If you want route model binding by slug, uncomment this.
     */
    // public function getRouteKeyName(): string
    // {
    //     return 'slug';
    // }
}
