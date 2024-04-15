<?php

namespace Core\Loaders\FilesLoad;

trait ConfigDatabaseFile
{
    /**
     * Default app config
     */
    private array $database = [
        'default' => 'pgsql',
        'connections' => [
            'pgsql' => [
                'driver' => 'pgsql',
            ],
            'mysql' => [
                'driver' => 'mysql',
            ],
        ],
    ];

    /**
     * Configuraciones de la base datos
     */
    public function databaseConfig(): array
    {
        return $this->database;
    }
}
