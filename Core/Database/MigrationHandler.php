<?php

namespace Core\Database;

use Core\Cli\Printer;
use Core\Database\Base\DB;
use Core\Implements\DatabaseMigrations;
use Core\Translate\Lang;
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
     * Crear la tabla de migraciones
     */
    public function createMigrationsTable(): array
    {
        // Crea la tabla de control de migraciones
        $migrationTable = new \Core\Database\Base\CreateMigrationsTable;
        $migrationTable->boot();
        $migrationTable->up();
        $sqlNewMigrationsTable = $migrationTable->createSql();
        $migrationTable->driver->runQuery($sqlNewMigrationsTable);

        $migrateDb = DB::make('migrations');
        $migrationList = $migrateDb->all();
        $current_batch = 0;

        // Determinar el batch actual
        foreach ($migrationList->toArray() as $migration) {
            if ($migration['batch'] > $current_batch) {
                $current_batch = $migration['batch'];
            }
        }


        return [
            'migrationList' => $migrationList,
            'next_batch'    => $current_batch + 1,
            'current_batch' => $current_batch,
            'migrateDb'     => $migrateDb,
        ];
    }

    /**
     * Ejecuta las Queries de migraciones
     */
    public function runQueries(string $type = 'up', Printer $printer): void
    {
        // Crear la tabla de migraciones
        [
            'migrationList' => $migrationList,
            'next_batch'    => $next_batch,
            'current_batch' => $current_batch,
            'migrateDb'     => $migrateDb
        ] = $this->createMigrationsTable();

        foreach ($this->migrations as $instance) {
            // No generar una nueva migración si ya existe
            if ($migrationList->where('migration', $instance['file_name'])->count() > 0 && $type === 'up') {
                continue;
            }

            if ($type === 'up') {
                $instance['instance']->up();
            } else if ($type === 'down') {
                $instance['instance']->down();
            }

            $sql = $instance['instance']->createSql();
            $instance['instance']->driver->runQuery($sql);

            if ($type === 'up') {
                $migrateDb->insert([
                    'migration' => $instance['file_name'],
                    'batch'     => $next_batch
                ]);

                $printer->color_cyan("\n- Migrate: " . $instance['file_name'])->toPrint();
            } else if ($type === 'down') {
                $migrateDb->delete('migration', $instance['file_name']);
                $printer->color_cyan("\n- Rollback: " . $instance['file_name'])->toPrint();
            }
        }

        $printer->color_green("\n\n" . Lang::_get('migrate.completed'))->toPrint();
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
