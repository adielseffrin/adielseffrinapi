<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WebSocketClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Http\Interfaces\Pizza\NotificationInterface',
            'App\Http\Repositories\Pizza\NotificationRepository'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
