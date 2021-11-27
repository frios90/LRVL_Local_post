<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{
    protected $table   = 'licenses';
    protected $guarded = [];
    use SoftDeletes;

    public function companies()
    {
        return $this->belongsToMany('\App\Models\Company', 'company_licenses')->withTrashed();
    }

    public function menus()
    {
        return $this->belongsToMany('\App\Models\Menu', 'license_menu')->withTrashed();
    }
}
