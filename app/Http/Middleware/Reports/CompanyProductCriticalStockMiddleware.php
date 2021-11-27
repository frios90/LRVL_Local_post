<?php

namespace App\Http\Middleware\Reports;

use Closure;
use App\Models\User;
use LicenseHelper;

class CompanyProductCriticalStockMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_profile_menu = User::whereHas('profile.menu', function ($query) {
                                        $query->where('menus.code', '=', 'M_R_CRITICAL_STOCK');
                                    })
                                    ->where('id', '=', \Auth::user()->id)
                                    ->first();
        $company_license = LicenseHelper::has('L_R_CRITIKL_STOCK');
        if ($user_profile_menu && $company_license) {
        return $next($request);
        } else {
        return back();
        }
    }
}
