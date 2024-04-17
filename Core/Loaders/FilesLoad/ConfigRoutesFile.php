<?php

namespace Core\Loaders\FilesLoad;

/**
 * Archivos de configuración 'app'
 */
trait ConfigRoutesFile
{
    protected array $routes = [
        'web' => [
            'files' => ['app/routes/web.php'],
            'prefix' => '',
            'middleware' => [],
            'name' => '',
            'rate_limit' => 60
        ],
        'api' => [
            'files' => [],
            'prefix' => '',
            'middleware' => [],
            'name' => '',
            'rate_limit' => 60
        ],
    ];

    /**
     * Routes config
     */
    public function routesConfig(): array
    {
        return $this->routes;
    }
}
