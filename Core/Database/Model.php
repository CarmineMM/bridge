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
    protected ?string $connection;

    /**
     * Driver de conexión
     */
    protected mixed $driver;

    /**
     * Constructor obligatorio
     */
    public function __construct()
    {
        $this->connection = $this->connection ?? Config::get('database.default', 'pgsql');
        $connectionConfig = Config::get("database.connections.{$this->connection}");

        $this->driver = match ($connectionConfig['driver']) {
            'pgsql' => new PostgresSQL($connectionConfig, $this->connection),
            default => throw new \Exception('Driver not found'),
        };

        // Configuración automática de la tabla
        if ($this->table === '') {
            $table = explode('\\', get_called_class());
            $this->table = strtolower(array_pop($table)) . 's';
        }
    }

    /**
     * Obtener todos los registros de la tabla
     */
    public function all(array $columns = ['*']): Collection
    {
        return new Collection($this->driver->all($this->table, $columns));
    }
}
