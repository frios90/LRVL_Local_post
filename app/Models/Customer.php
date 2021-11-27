<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{

    protected $guarded = [];
    use SoftDeletes;

    public function region()
    {
        return $this->belongsTo('\App\Models\Region');
    }

    public function commune()
    {
        return $this->belongsTo('\App\Models\Commune');
    }
}
