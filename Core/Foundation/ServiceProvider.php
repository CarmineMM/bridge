<?php

namespace Core\Foundation;

class ServiceProvider
{
    /**
     * Clase encargada del manejo de excepciones Http
     */
    protected mixed $httpException;

    /**
     * Observadores de los modelos
     */
    protected array $observers = [];

    /**
     * Registra los servicios de la aplicación
     */
    public function register(bool $consoleMode, ?Request $request): void
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

    /**
     * Obtiene las props necesarias para instancia en el contenedor
     */
    public function getObservers(): array
    {
        return $this->observers;
    }
}
