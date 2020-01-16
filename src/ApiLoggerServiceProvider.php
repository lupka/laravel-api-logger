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
            __DIR__.'/../config/api_logger.php' => config_path('api_logger.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->app['router']->aliasMiddleware('api-logger', \Lupka\ApiLogger\Middleware\ApiLogger::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Lupka\ApiLogger\Console\Commands\ClearApiLogs::class,
            ]);
        }
    }
}
