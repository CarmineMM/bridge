<?php

namespace Core\Database;

use Core\Implements\DatabaseMigrations;
use Database\Migrations;

class MigrationHandler implements DatabaseMigrations
{
    /**
     * Path a la ubicación de las migraciones
     *
     * @var string
     */
    protected string $migration_path = 'database/migrations';

    /**
     * Instancias de los archivos de migraciones
     *
     * @var string
     */
    private array $migration_instance = [];

    /**
     * Ejecuta las Queries de migraciones
     */
    private function runQueries()
    {
    }

    /**
     * Obtiene el listado de archivos de las migraciones y las instancia
     *
     * @return void
     */
    public function getMigrationsFiles(): void
    {
        $files = glob(ROOT_PATH . $this->migration_path . DIRECTORY_SEPARATOR . '*.php');

        foreach ($files as $file) {
            $class = 'Database\\migrations\\' . pathinfo($file, PATHINFO_FILENAME);

            $instance = new $class;
            $instance->boot();
            $instance->up();
            $sql = $instance->createSql();
            $instance->driver->runQuery($sql);
        }
    }
}
