<?php

namespace Core\Foundation\RateLimit;

use Core\Foundation\RateLimit\Driver\Session;
use Core\Foundation\Request;

class RateLimit
{
    /**
     * Driver
     */
    private ?Session $driver;

    /**
     * Excluir rutas
     *
     * @var array
     */
    protected array $excludeRoutes = [
        '/__bridge-debugbar-css',
        '/__bridge-debugbar-js',
        '/__bridge-deps-js'
    ];

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

        if (in_array($request->uri, $this->excludeRoutes)) {
            return;
        }

        $this->driver->check($request);
        $this->driver->increment();
    }
}
