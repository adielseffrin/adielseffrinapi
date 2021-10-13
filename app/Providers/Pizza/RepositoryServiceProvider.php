<?php

namespace App\Providers\Pizza;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Http\Interfaces\Pizza\IngredientesUsuarioInterface',
            'App\Http\Repositories\Pizza\IngredientesUsuarioRepository'
        );

        $this->app->bind(
            'App\Http\Interfaces\Pizza\TrocaIngredienteInterface',
            'App\Http\Repositories\Pizza\TrocaIngredienteRepository'
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
