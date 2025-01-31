<?php

namespace Lotuashvili\LaravelBillwerk;

use Illuminate\Support\ServiceProvider;

class LaravelBillwerkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/resources/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        $this->publishes([
            __DIR__ . '/resources/config/billwerk.php' => config_path('billwerk.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
