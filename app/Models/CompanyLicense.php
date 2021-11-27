<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyLicense extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $table = 'company_licenses';

}
