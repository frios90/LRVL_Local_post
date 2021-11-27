<?php

namespace App\Http\Middleware\ToController;

use Closure;
use App\Models\User;
use LicenseHelper;
class MenuMiddleware
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
                                    $query->where('menus.route', '=', '/menus');
                                })
                                ->where('id', '=', \Auth::user()->id)
                                ->first();
        $company_license = LicenseHelper::has('L_M_MENUS');
        if ($user_profile_menu && $company_license) {
            return $next($request);
        } else {
            return back();
        }
    }
}
