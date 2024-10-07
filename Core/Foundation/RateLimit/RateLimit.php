<?php

namespace Core\Foundation\RateLimit;

use Core\Foundation\Debugging;
use Core\Foundation\RateLimit\Driver\Session;
use Core\Foundation\Request;

class RateLimit
{
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
        $request = Request::make();

        if (in_array($request->uri, Debugging::$debugRoutes)) {
            return;
        }

        $this->driver->check($request);
        $this->driver->increment();
    }
}
