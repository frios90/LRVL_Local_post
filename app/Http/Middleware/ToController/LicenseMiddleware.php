<?php

namespace App\Http\Middleware\ToController;

use Closure;
use App\Models\User;
use LicenseHelper;
class LicenseMiddleware
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
                                    $query->where('menus.route', '=', '/licenses');
                                })
                                ->where('id', '=', \Auth::user()->id)
                                ->first();
        $company_license = LicenseHelper::has('L_M_LICENSES');
        if ($user_profile_menu && $company_license) {
            return $next($request);
        } else {
            return back();
        }
    }
}
