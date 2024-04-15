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
            'name' => ''
        ],
        'api' => [
            'files' => [],
            'prefix' => '',
            'middleware' => [],
            'name' => ''
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
