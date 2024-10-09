<?php

namespace Core\Loaders\FilesLoad;

trait ConfigBridgeWireFile
{
    /**
     * Default app config
     */
    private array $bridgeWire = [
        'enabled' => true,
        'namespace' => 'App\BridgeWire',
    ];

    /**
     * Configuraciones de la base datos
     */
    public function bridgeWireConfig(): array
    {
        return $this->bridgeWire;
    }
}
