<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    public function subMenus()
    {
        return $this->hasMany('\App\Models\Menu','parent_id');
    }

    public function parentMenu()
    {
        return $this->belongsTo('\App\Models\Menu','parent_id');
    }

    public function profile()
    {
        return $this->belongsToMany('\App\Models\Code', 'menu_profile', 'menu_id', 'profile_id')->withTrashed();
    }

    public function licenses()
    {
        return $this->belongsToMany('\App\Models\License', 'license_menu', 'menu_id', 'license_id')->withTrashed();
    }
}
