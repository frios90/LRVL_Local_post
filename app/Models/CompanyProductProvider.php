<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyProductProvider extends Model
{

    use SoftDeletes;
    protected $table = 'company_product_provider';
    protected $guarded = [];   

    public function provider()
    {
        return $this->belongsTo('\App\Models\Provider');
    }
}
