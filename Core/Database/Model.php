<?php

namespace Core\Database;

use Core\Database\Driver\PostgresSQL;
use Core\Loaders\Config;
use Core\Support\Collection;
use PDO;

class Model
{
    /**
     * Tabla en la base de datos
     */
    protected string $table = '';

    /**
     * Llave primaria de la tabla
     */
    protected string $primaryKey = 'id';

    /**
     * Columnas que se pueden editar y crear
     */
    protected array $fillable = [];

    /**
     * Configuración de conexión a la base de datos,
     * almacenado en las configuraciones.
     */
    public ?string $connection;

    /**
     * Driver de conexión
     */
    protected mixed $driver;

    /**
     * Constructor obligatorio
     */
    public function __construct()
    {
        // Configuración automática de la tabla
        if ($this->table === '') {
            $table = explode('\\', get_called_class());
            $this->table = strtolower(array_pop($table)) . 's';
        }

        $this->connection = $this->connection ?? Config::get('database.default', 'pgsql');
        $connectionConfig = Config::get("database.connections.{$this->connection}");

        $this->driver = match ($connectionConfig['driver']) {
            'pgsql' => new PostgresSQL($connectionConfig, $this),
            default => throw new \Exception('Driver not found'),
        };
    }

    /**
     * Obtener la tabla
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Obtener la llave primaria
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * Obtener todos los registros de la tabla
     */
    public function all(array $columns = ['*']): Collection
    {
        return new Collection($this->driver->all($columns));
    }

    /**
     * Obtener todos los registros de la tabla
     */
    public static function getAll(array $columns = ['*']): Collection
    {
        return (new static)->all($columns);
    }

    /**
     * Busca un registro especifico, devuelve solo 1 elemento
     */
    public function find(string|int $findByPrimaryKey): Collection
    {
        return new Collection(
            $this->driver->find($findByPrimaryKey)[0] ?? []
        );
    }
}
