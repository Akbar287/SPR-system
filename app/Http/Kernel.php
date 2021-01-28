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
        \App\Http\Middleware\TrustProxies::class,
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
            'bindings',
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
        'cekStatus' => \App\Http\Middleware\cekStatus::class,
        'cekAdmin' => \App\Http\Middleware\cekAdmin::class,
        'cekReseller' => \App\Http\Middleware\cekReseller::class,
        'cekCart' => \App\Http\Middleware\cekCart::class,
        'cekIncome' => \App\Http\Middleware\cekIncome::class,
        'cekOutcome' => \App\Http\Middleware\cekOutcome::class,
        'cekPurchase' => \App\Http\Middleware\cekPurchase::class,
        'cekCapital' => \App\Http\Middleware\cekCapital::class,
        'cekCapital' => \App\Http\Middleware\cekCapital::class,
        'cekProduct' => \App\Http\Middleware\cekProduct::class,
        'cekCategory' => \App\Http\Middleware\cekCategory::class,
        'cekProducted' => \App\Http\Middleware\cekProducted::class,
        'cekSupplier' => \App\Http\Middleware\cekSupplier::class,
        'cekPesanan' => \App\Http\Middleware\cekPesanan::class,
        'cekOrder' => \App\Http\Middleware\cekOrder::class,
        'cekMaterial' => \App\Http\Middleware\cekMaterial::class,
        'cekMarket' => \App\Http\Middleware\cekMarket::class,
        'ceklistAdmin' => \App\Http\Middleware\ceklistAdmin::class,
        'cekDiskon' => \App\Http\Middleware\cekDiskon::class,
        'cekPrint' => \App\Http\Middleware\cekPrint::class,
        'cekExpense' => \App\Http\Middleware\cekExpense::class,
        'cekSalary' => \App\Http\Middleware\cekSalary::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
