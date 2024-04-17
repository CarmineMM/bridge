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
     *
     * @var integer
     */
    private ?mixed $driver;

    /**
     * Construct
     */
    public function __construct(string $useDriver)
    {
        $this->driver = match ($useDriver) {
            'session' => new Session,
            default => throw new \Exception("Driver {$useDriver} not found"),
        };
    }
}
