<?php

namespace App\Providers;

use App\Exceptions\CustomExceptionHandler;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(Exceptions::class, CustomExceptionHandler::class);
    }
}
