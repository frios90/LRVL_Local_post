<?php 

namespace App\Helpers;
use App\Models\License;

class LicenseHelper
{
    public static function has(string $license)
    {
        $user = \Auth::user();
        $license = License::whereHas('companies', function ($query) use ($user){
                                $query->where('companies.id', '=', $user->company_id);
                                $query->whereNull('company_licenses.deleted_at');
                            })
                            ->where('code', '=', $license)
                            ->first();
                           
        if ($license) {
            return true;
        } else {
            return false;
        }        
    }
}