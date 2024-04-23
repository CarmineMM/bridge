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
     * @var Array<Database\Migrations>
     */
    private array $migrations = [];

    /**
     * Ejecuta las Queries de migraciones
     */
    public function runQueries(string $type = 'up')
    {
        // Crear la tabla de migraciones
        $migrationTable = new \Core\Database\Base\CreateMigrationTable();
        $migrationTable->boot();
        $migrationTable->up();

        foreach ($this->migrations as $instance) {
            $type === 'up' ? $instance['instance']->up() : $instance['instance']->down();

            $sql = $instance['instance']->createSql();
            $instance['instance']->driver->runQuery($sql);
        }
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

            $this->migrations[] = [
                'instance'  => $instance,
                'file_path' => $file,
                'file_name' => pathinfo($file, PATHINFO_FILENAME)
            ];
        }
    }
}
