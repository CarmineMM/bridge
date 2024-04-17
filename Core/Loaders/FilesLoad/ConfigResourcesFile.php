<?php

namespace Core\Loaders\FilesLoad;

trait ConfigResourcesFile
{
    /**
     * Recursos
     */
    private array $resources = [
        'view_path' => 'app/resources/views',
        'http_exceptions' => [
            '400' => 'resources/views/errors/400.php',
            '404' => 'resources/views/errors/404.php',
        ],
    ];

    /**
     * Routes config
     */
    public function resourcesConfig(): array
    {
        return $this->resources;
    }
}
