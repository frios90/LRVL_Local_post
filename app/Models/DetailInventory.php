<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailInventory extends Model
{
    protected $guarded = [];
    protected $table   = 'detail_inventories';
    use SoftDeletes;

    public function companyProduct()
    {
        return $this->belongsTo('\App\Models\CompanyProduct', 'product_company_id')->withTrashed();
    }
}
