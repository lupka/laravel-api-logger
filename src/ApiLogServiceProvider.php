<?php

namespace Lupka\ApiLog;

use Illuminate\Support\ServiceProvider;

class ApiLogServiceProvider extends ServiceProvider
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
        $result = $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
