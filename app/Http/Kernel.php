<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'intranet' => \App\Http\Middleware\IntranetMiddleware::class,
        /**Estas son las declaraciones de middleware particulares para cada controlador en cuando al perfil de usuario y sua acceso */
        'toProfile' => \App\Http\Middleware\ToController\ProfileMiddleware::class,
        'toCode'    => \App\Http\Middleware\ToController\CodeMiddleware::class,
        'toCompany' => \App\Http\Middleware\ToController\CompanyMiddleware::class,
        'toMenu'    => \App\Http\Middleware\ToController\MenuMiddleware::class,
        'toProduct' => \App\Http\Middleware\ToController\ProductMiddleware::class,
        'toUser'    => \App\Http\Middleware\ToController\UserMiddleware::class,
        'toProductCompany' => \App\Http\Middleware\ToController\ProductCompanyMiddleware::class,
        'toProvider' => \App\Http\Middleware\ToController\ProviderMiddleware::class,
        'toReception' => \App\Http\Middleware\ToController\ReceptionMiddleware::class,
        'toInventory' => \App\Http\Middleware\ToController\InventoryMiddleware::class,
        'toBrand' => \App\Http\Middleware\ToController\BrandMiddleware::class,
        'toLicense' => \App\Http\Middleware\ToController\LicenseMiddleware::class,
        'toCategory' => \App\Http\Middleware\ToController\CategoryMiddleware::class,
        'toFolio' => \App\Http\Middleware\ToController\FolioMiddleware::class,
        'toMonoCompanyProduct' => \App\Http\Middleware\ToController\MonoCompanyProductMiddleware::class,
        'toCustomer' => \App\Http\Middleware\ToController\CustomerMiddleware::class,
        'toUserCompany' => \App\Http\Middleware\ToController\UserCompanyMiddleware::class,
        'toTicket' => \App\Http\Middleware\ToController\SaleTicketMiddleware::class,
        'toSales' => \App\Http\Middleware\ToController\SaleMiddleware::class,

        'toAvailableGeolocation' => \App\Http\Middleware\ToLicenses\AvailableGeolocationMiddleware::class,
        'toAvailableAutomaticProductAllocation' => \App\Http\Middleware\ToLicenses\AvailableAutomaticProductAllocationMiddleware::class,
        'toAvailableMonoCompany' => \App\Http\Middleware\ToLicenses\AvailableMonoCompanyMiddleware::class,
        'toAvailableMultiCompany' => \App\Http\Middleware\ToLicenses\AvailableMultiCompanyMiddleware::class,

        /**Para reportes */


        'toReportCriticalStock' => \App\Http\Middleware\Reports\CompanyProductCriticalStockMiddleware::class,


    ];
}
