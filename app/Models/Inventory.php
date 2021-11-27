<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    protected $guarded = [];
    use SoftDeletes;
    protected $table = "inventories";

    public function detailInventory()
    {
        return $this->hasMany('\App\Models\DetailInventory');
    }

    public function inventoryStatus()
    {
        return $this->hasOne('\App\Models\Code', 'id', 'inventory_status_id');
    }

    public function user()
    {
        return $this->belongsTo('\App\Models\User');
    }
}
