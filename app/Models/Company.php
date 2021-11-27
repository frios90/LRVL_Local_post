<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    public function products()
    {
        return $this->belongsToMany('\App\Models\Product', 'company_products');
    }

    public function companyGeolocation()
    {
        return $this->hasMany('\App\Models\CompanyGeolocation');
    }

    public function region()
    {
        return $this->belongsTo('\App\Models\Region');
    }

    public function commune()
    {
        return $this->belongsTo('\App\Models\Commune');
    }
}
