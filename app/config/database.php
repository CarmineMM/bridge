<?php

use Core\Support\Env;

return [
    /**
     * Default database connection
     */
    'default' => Env::get('DB_CONNECTION', 'pgsql'),

    /**
     * Controlador de migraciones de migraciones
     */
    'migration_handler' => \Database\Migrator::class,

    /**
     * Listado de conexiones administrable por base de datos
     */
    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'url' => Env::get('DB_URL'),
            'host' => Env::get('DB_HOST', '127.0.0.1'),
            'username' => Env::get('DB_USERNAME', 'pgsql'),
            'password' => Env::get('DB_PASSWORD', 'pgsql'),
            'database' => Env::get('DB_DATABASE', 'pgsql'),
            'port' => Env::get('DB_PORT', 5432),
            'charset' => 'utf8',
            'options' => [],
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => Env::get('DB_URL'),
            'host' => Env::get('DB_HOST', '127.0.0.1'),
            'username' => Env::get('DB_USERNAME', 'mysql'),
            'password' => Env::get('DB_PASSWORD', 'mysql'),
            'database' => Env::get('DB_DATABASE', 'mysql'),
            'port' => Env::get('DB_PORT', 3306),
            'charset' => 'utf8mb4',
            'options' => [],
        ],
    ],
];
