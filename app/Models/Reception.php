<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reception extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    public function detailReception()
    {
        return $this->hasMany('\App\Models\DetailReception');
    }

    public function receptionStatus()
    {
        return $this->hasOne('\App\Models\Code', 'id', 'reception_status_id');
    }

    public function provider()
    {
        return $this->belongsTo('\App\Models\Provider');
    }

    public function user()
    {
        return $this->belongsTo('\App\Models\User');
    }
}
