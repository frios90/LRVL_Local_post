<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyProduct extends Model
{

    protected $guarded = [];
    protected $table   = 'company_products';
    use SoftDeletes;

    public function product()
    {
        return $this->belongsTo('\App\Models\Product');
    }

    public function stocks()
    {
        return $this->hasMany('\App\Models\CompanyProductStock', 'company_product_id', 'id');
    }

    public function providers()
    {
        return $this->belongsToMany('\App\Models\Provider', 'company_product_provider')->withPivot('provider_price');
    }

    public function productProvider()
    {
        return $this->hasMany('\App\Models\CompanyProductProvider', 'company_product_id', 'id')->withTrashed()->orderBy('created_at', 'desc');
    }
}
