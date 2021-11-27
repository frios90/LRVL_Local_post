<?php

namespace App\Http\Middleware\ToLicenses;

use Closure;
use LicenseHelper;
class AvailableMultiCompanyMiddleware
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
        if (LicenseHelper::has('L_MULTI_COMPANY')) {
            return $next($request);
        } else {
            return back();
        }
    }
}
