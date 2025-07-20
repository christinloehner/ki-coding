<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/**
 * Determine the correct base path for Laravel
 */
$basePath = getenv('APP_BASE_PATH') ?: dirname(__DIR__);

return Application::configure(basePath: $basePath)
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\WikiSecurity::class,
        ]);
        
        $middleware->alias([
            'check.ban' => \App\Http\Middleware\BanCheck::class,
            'rate.limit.wiki' => \App\Http\Middleware\RateLimitWiki::class,
            'wiki.security' => \App\Http\Middleware\WikiSecurity::class,
            'honeypot' => \App\Http\Middleware\HoneypotMiddleware::class,
            'throttle:markdown' => \Illuminate\Routing\Middleware\ThrottleRequests::class.':60,1',
            'throttle:api' => \Illuminate\Routing\Middleware\ThrottleRequests::class.':100,1',
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
