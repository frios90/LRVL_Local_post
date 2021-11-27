<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleTicket extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('\App\Models\User');
    }

    public function code()
    {
        return $this->belongsTo('\App\Models\Code');
    }

    public function step()
    {
        return $this->belongsTo('\App\Models\Code', 'step_id');
    }

    public function customer()
    {
        return $this->belongsTo('\App\Models\Customer');
    }

    public function detail()
    {
        return $this->hasMany('\App\Models\SaleTicketDetail', 'sale_ticket_id');
    }

  
}
