<?php

namespace Core\Database;

use Core\Implements\DatabaseMigrations;

class MigrationHandler implements DatabaseMigrations
{
    /**
     * Indica si se debe crear la base de datos si no existe.
     */
    protected $create_database_if_not_exists = true;

    /**
     * Path a la ubicación de las migraciones
     *
     * @var string
     */
    protected string $migration_path = 'database/migrations';

    /**
     * Ejecuta las Queries de migraciones
     */
    private function runQueries()
    {
    }

    /**
     * Ejecutar migraciones
     *
     * @return array Retorna los archivos migrados
     */
    public function migrate(): array
    {
        return [];
    }

    /**
     * Obtiene el listado de archivos de las migraciones y las instancia
     *
     * @return void
     */
    public function getMigrationsFiles(): void
    {
    }
}
