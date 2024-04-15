<?php

namespace Core\Loaders\FilesLoad;

trait ConfigResourcesFile
{
    /**
     * Recursos
     */
    private array $resources = [
        'view_path' => 'app/resources/views',
    ];

    /**
     * Routes config
     */
    public function resourcesConfig(): array
    {
        return $this->resources;
    }
}
