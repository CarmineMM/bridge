<?php

namespace Core\Foundation;

class ServiceProvider
{
    /**
     * Clase encargada del manejo de excepciones Http
     */
    protected mixed $httpException;

    /**
     * Registra los servicios de la aplicación
     */
    public function register(): void
    {
        // Registrar servicios
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
