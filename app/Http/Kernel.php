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
    protected $middlewareGroups = [
        'api' => [
            \App\Http\Middleware\AuthenticateMiddleware::class,
        ],
    ];

    protected $middleware = [
        // Trusts proxies
        \Illuminate\Http\Middleware\TrustProxies::class,
        // Handles maintenance mode
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
        // Validates post size
        \Illuminate\Http\Middleware\ValidatePostSize::class,
        // Trims strings
        // \App\Http\Middleware\TrimStrings::class,
        // Converts empty strings to null
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */


    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
      */
    // protected $routeMiddleware = [
    //     'auth' => \App\Http\Middleware\Authenticate::class,
    //     'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    //     'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
    //     'can' => \Illuminate\Auth\Middleware\Authorize::class,
    //     'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    //     'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
    //     'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
    //     'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    //     'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    // ];
}
