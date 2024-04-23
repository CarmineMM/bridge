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
    protected ?string $connection = null;

    /**
     * Driver de conexión
     */
    protected ?MigratePostgresSQL $driver = null;

    /**
     * Listado del SQL para crear columnas
     */
    private array $columnsCreated = [];

    /**
     * Creador de columnas
     */
    private ?CreatorColumn $creatorColumn = null;

    /**
     * Boot method
     *
     * @return void
     */
    public function boot(): void
    {
        $this->connection = $this->connection ?? Config::get('database.default', 'pgsql');
        $connectionConfig = Config::get("database.connections.{$this->connection}");

        $this->driver = match ($connectionConfig['driver']) {
            'pgsql' => new MigratePostgresSQL($connectionConfig, new Model),
            default => throw new Exception("No se ha definido la conexión {$this->connection}"),
        };

        $this->creatorColumn = new CreatorColumn($this->driver);
    }

    /**
     * Genera SQL para crear la tabla
     *
     * @param string $table_name
     * @return static
     */
    protected function table(string $table_name): static
    {
        $this->sql = "CREATE TABLE IF NOT EXISTS {$table_name} ({columns})";
        return $this;
    }

    /**
     * Agrega columna a la tabla
     */
    protected function column(callable $call): static
    {
        $this->columnsCreated[] = $call($this->creatorColumn);
        return $this;
    }
}
