<?php

namespace Core\Database;

use Core\Database\Driver\MigratePostgresSQL;
use Core\Loaders\Config;
use Exception;

class Table
{
    /**
     * SQL a generar
     *
     * @var string
     */
    private string $sql = '';

    /**
     * Conexión en la lista de configuraciones
     */
    protected string $connection = 'default';

    /**
     * Driver de conexión
     */
    protected MigratePostgresSQL $driver;

    /**
     * Boot method
     *
     * @return void
     */
    private function boot(): void
    {
        $this->connection = $this->connection ?? Config::get('database.default', 'pgsql');
        $connectionConfig = Config::get("database.connections.{$this->connection}");

        $this->driver = match ($this->connection) {
            'pgsql' => new MigratePostgresSQL(
                $connectionConfig,
                new Model
            ),
            default => throw new Exception("No se ha definido la conexión {$this->connection}"),
        };
    }

    /**
     * Genera SQL para crear la tabla
     *
     * @param string $table_name
     * @return static
     */
    protected function table(string $table_name): static
    {
        $this->driver->table($table_name);
        return $this;
    }
}
