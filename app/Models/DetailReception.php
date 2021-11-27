<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailReception extends Model
{
    protected $guarded = [];
    protected $table   = 'detail_receptions';
    use SoftDeletes;

    public function companyProduct()
    {
        return $this->belongsTo('\App\Models\CompanyProduct', 'product_company_id')->withTrashed();
    }
}
