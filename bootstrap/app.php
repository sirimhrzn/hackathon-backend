<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\VendorResource;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        using: function(){
            Route::prefix('api/v1/vendor/{vendor_id}')
                ->group(base_path('routes/vendor.php'));
            Route::prefix('api/v1/global')
                ->group(base_path('routes/api.php'));
        }
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
