<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Code extends Model
{
    protected $table   = 'codes';
    protected $guarded = [];
    use SoftDeletes;

    public function allSons()
    {
        return $this->hasMany('\App\Models\Code', 'type_id', 'id')->withTrashed();
    }

    public function parent()
    {
        return $this->belongsTo('\App\Models\Code', 'type_id', 'id')->withTrashed();
    }

    public function menu()
    {
        return $this->belongsToMany('\App\Models\Menu', 'menu_profile', 'profile_id', 'menu_id')->withTrashed();
    }

    public function products()
    {
        return $this->hasMany('\App\Models\Product', 'category_id', 'id')->withTrashed();
    }

    public function brandProducts()
    {
        return $this->hasMany('\App\Models\Product', 'brand_id', 'id')->withTrashed();
    }

    public function profileUsers()
    {
        return $this->hasMany('\App\Models\User', 'profile_id', 'id')->withTrashed();
    }
}
