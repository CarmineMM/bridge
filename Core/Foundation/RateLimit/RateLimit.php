<?php

namespace Core\Foundation\RateLimit;

use Core\Foundation\RateLimit\Driver\Session;

class RateLimit
{
    /**
     * Identificador para el control de usuario,
     * esto puede ser la IP o el ID de usuario autenticado.
     */
    private string $identification = '';

    /**
     * Driver
     */
    private ?Session $driver;

    /**
     * Construct
     */
    public function __construct(string $useDriver, int $limit, int $banTime = 0)
    {
        $this->driver = match ($useDriver) {
            'session' => new Session($limit, $banTime),
            default => throw new \Exception("Driver {$useDriver} not found", 500),
        };
    }

    /**
     * Verifica si el usuario ha alcanzado el limite de peticiones
     */
    public function roadmap(): void
    {
        $this->driver->check();
        $this->driver->increment();
    }
}
