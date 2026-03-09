<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{

    protected $fillable = ['product_id','key','label','input_type','unit','min','max','step','options_json','is_required'];

    protected $casts = ['options_json'=>'array','is_required'=>'boolean'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function prices()
    {
        return $this->hasMany(FeaturePrice::class, 'product_feature_id');
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }
    
}
