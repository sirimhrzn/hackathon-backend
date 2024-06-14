<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\VendorResource;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withRouting(
        api: base_path('routes/vendor.php'),
        apiPrefix: 'api/v1/vendor/{vendor_id}'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'vendor' => VendorResource::class
        ])
        ->validateCsrfTokens(except:["*"]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
