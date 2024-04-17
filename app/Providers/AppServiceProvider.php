<?php

namespace App\Providers;

use Core\Foundation\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(bool $consoleMode): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Estos se ejecutan solo cuando las rutas de acceso sean coincidentes 
     * En otras palabras no sea un 404 y no este en modo consola.
     */
    public function boot(): void
    {
        //
    }
}
