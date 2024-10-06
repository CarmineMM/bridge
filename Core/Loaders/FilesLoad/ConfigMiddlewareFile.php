<?php

namespace Core\Loaders\FilesLoad;

trait ConfigMiddlewareFile
{
    /**
     * Default middleware config
     */
    private array $middleware = [
        'app' => [],
        'web' => [],
        'api' => [],
        'named' => [],
    ];

    /**
     * Configuraciones de la base datos
     */
    public function middlewareConfig(): array
    {
        return $this->middleware;
    }
}
