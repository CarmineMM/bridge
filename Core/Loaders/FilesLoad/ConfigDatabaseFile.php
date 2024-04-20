<?php

namespace Core\Loaders\FilesLoad;

trait ConfigDatabaseFile
{
    /**
     * Default app config
     */
    private array $database = [
        'default' => 'pgsql',
        'migration_handler' => '',
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
