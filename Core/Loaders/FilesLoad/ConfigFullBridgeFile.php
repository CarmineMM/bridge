<?php

namespace Core\Loaders\FilesLoad;

trait ConfigFullBridgeFile
{
    /**
     * Default app config
     */
    private array $fullBridge = [
        'enabled' => false,
        'namespace' => 'App\FullBridge',
    ];

    /**
     * Configuraciones de la base datos
     */
    public function fullBridgeConfig(): array
    {
        return $this->fullBridge;
    }
}
