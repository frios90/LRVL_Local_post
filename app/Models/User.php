<?php //b4f2e6d787e3632e35b6465fb958eef5

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    public function requests()
    {
        return $this->hasMany('\App\Models\Request');
    }

    public function staff()
    {
        return $this->belongsToMany('\App\Models\Request', 'request_user', 'user_id', 'request_id');
    }

    public function area()
    {
        return $this->belongsTo('\App\Models\Area');
    }

    public function profile()
    {
        return $this->belongsTo('\App\Models\Code', 'profile_id');
    }

    public function projects()
    {
        return $this->hasMany('\App\Models\Project', 'customer_id')->withTrashed();
    }

    public function company()
    {
        return $this->belongsTo('\App\Models\Company', 'company_id');
    }

    public function userGeolocation()
    {
        return $this->hasMany('\App\Models\UserGeolocation');
    }

    public function images()
    {
        return $this->hasMany('\App\Models\UserImage', 'user_id');
    }

    public function region()
    {
        return $this->belongsTo('\App\Models\Region');
    }

    public function commune()
    {
        return $this->belongsTo('\App\Models\Commune');
    }

 
}
