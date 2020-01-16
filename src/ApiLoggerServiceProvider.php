<?php

namespace Lupka\ApiLogger;

use Illuminate\Support\ServiceProvider;

class ApiLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/api-logger.php' => config_path('api-logger.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->app['router']->aliasMiddleware('api-logger', \Lupka\ApiLogger\Middleware\ApiLogger::class);
    }
}
