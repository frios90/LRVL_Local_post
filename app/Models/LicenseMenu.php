<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LicenseMenu extends Model
{
    protected $guarded = [];
    protected $table = 'license_menu';
    use SoftDeletes;

}
