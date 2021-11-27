<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folio extends Model
{
    protected $table   = 'folios';
    protected $guarded = [];
    use SoftDeletes;

    public function document() 
    {
        return $this->belongsTo('\App\Models\Code', 'type_folio_id');
    }
}
