<?php

namespace Core\Loaders\FilesLoad;

trait ConfigFullBridgeFile
{
    /**
     * Default app config
     */
    private array $fullBridge = [
        'enable' => false,
        'namespace' => 'App\FullBridge',
        'url-post' => '/full-bridge-actions',
    ];

    /**
     * Configuraciones de la base datos
     */
    public function fullBridgeConfig(): array
    {
        return $this->fullBridge;
    }
}
