<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    public function category() 
    {
        return $this->belongsTo('\App\Models\Code', 'category_id')->withTrashed();
    }

    public function brand()
    {
        return $this->belongsTo('\App\Models\Code', 'brand_id')->withTrashed();
    }

    public function companies()
    {
        return $this->belongsToMany('\App\Models\Company', 'company_products');
    }

    public function images()
    {
        return $this->hasMany('\App\Models\ProductImage', 'product_id');
    }
}
