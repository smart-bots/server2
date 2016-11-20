<?php

namespace SmartBots\Http;

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
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        // \SmartBots\Http\Middleware\SslProtocol::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        // 'web' => [
        //     \SmartBots\Http\Middleware\EncryptCookies::class,
        //     \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        //     \Illuminate\Session\Middleware\StartSession::class,
        //     \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        //     \SmartBots\Http\Middleware\VerifyCsrfToken::class,
        //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // ],

        'api' => [
            'cors',
            'throttle:60,1', // 60 requests per minute, wait 1 mitute when hits the limit
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
        // 'auth'        => \Illuminate\Auth\Middleware\Authenticate::class,
        // 'auth.basic'  => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings'    => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can'         => \Illuminate\Auth\Middleware\Authorize::class,
        'throttle'    => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        // 'jwt.auth'    => 'Tymon\JWTAuth\Middleware\GetUserFromToken',
        // 'jwt.refresh' => 'Tymon\JWTAuth\Middleware\RefreshToken',
        'authed' => \SmartBots\Http\Middleware\Authenticated::class,
        'nonAuthed' => \SmartBots\Http\Middleware\NonAuthenticated::class,
        'hubSelected' => \SmartBots\Http\Middleware\HubSelected::class,
    ];
}
