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
     * Alteraciones a la tabla
     */
    private array $alterSql = [];

    /**
     * Conexión en la lista de configuraciones
     */
    protected ?string $connection = null;

    /**
     * Driver de conexión
     */
    public ?MigratePostgresSQL $driver = null;

    /**
     * Listado del SQL para crear columnas
     */
    private array $columnsCreated = [];

    /**
     * Creador de columnas
     */
    private ?CreatorColumn $creatorColumn = null;

    /**
     * Nombre de la tabla
     */
    protected string $table_name = '';

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
    protected function table(string $table_name = ''): static
    {
        if (!empty($table_name)) {
            $this->table_name = $table_name;
        }

        $this->sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} ([columns]);";
        $this->creatorColumn->setTableName($this->table_name);
        return $this;
    }

    /**
     * Elimina la tabla
     */
    protected function dropTable(string $table_name = ''): static
    {
        if (!empty($table_name)) {
            $this->table_name = $table_name;
        }

        $this->sql = "DROP TABLE IF EXISTS {$this->table_name};";

        return $this;
    }

    /**
     * Agrega columna a la tabla
     */
    protected function column(callable $call): static
    {
        $callable = $call($this->creatorColumn);
        $this->columnsCreated[] = $callable->_get();
        $this->alterSql = array_merge($this->alterSql, $callable->getAlterSql());
        return $this;
    }

    /**
     * Crea el SQL final
     */
    public function createSql(): string
    {
        $this->sql = str_replace('[columns]', implode(', ', $this->columnsCreated), $this->sql);
        $this->sql .= implode(' ', $this->alterSql);
        return $this->sql;
    }
}
