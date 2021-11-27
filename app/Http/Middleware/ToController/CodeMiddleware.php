<?php

namespace App\Http\Middleware\ToController;

use Closure;
use App\Models\User;
use LicenseHelper;
class CodeMiddleware
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
        $user = User::where('id', '=', \Auth::user()->id)->with('profile.menu')->first();
        $has_permissions = false;
        foreach ($user->profile->menu as $menu) {
            if ($menu->route == '/codes') {
                $has_permissions = true;
            }
        }
        if ($has_permissions) {
            return $next($request);
        } else {
            return back();
        }
    }
}
