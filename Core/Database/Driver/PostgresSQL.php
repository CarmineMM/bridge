<?php

namespace Core\Database\Driver;

use Core\Database\Base\SQLBaseDriver;
use Core\Database\Model;
use Core\Implements\DatabaseDriver;

/**
 * Driver para PostgreSQL
 */
class PostgresSQL extends SQLBaseDriver implements DatabaseDriver
{
    /**
     * Constructor
     */
    public function __construct(
        /**
         * Configuraciones del desarrollador
         */
        public array $config,
        /**
         * Tipo de conexión
         */
        Model $model
    ) {
        $dsn = "pgsql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']}";

        try {
            $this->pdo = new \PDO($dsn, $this->config['username'], $this->config['password'], $this->config['options'] ?? []);
        } catch (\PDOException $th) {
            throw new \Exception("Bridge Driver PostgreSQL: " . $th->getMessage(), 500);
        }

        $this->model = $model;
    }

    /**
     * Obtiene todos los registros de la tabla
     */
    public function all(array $columns = ['*']): array
    {
        $this->instance('select');

        $this->columns = $columns;

        return $this->exec(
            'pgsql',
            $this->model->connection
        );
    }

    /**
     * Adjunta un WHERE a la query, limitando al primary key
     */
    public function find(string|int $find): array
    {
        return $this
            ->where($this->model->getPrimaryKey(), $find)
            ->limit(1)
            ->get();
    }

    /**
     * Obtener los resultados
     */
    public function get(array $columns = ['*']): array
    {
        $this->instance('select');
        $this->columns = $columns;

        return $this->exec(
            'pgsql',
            $this->model->connection
        );
    }

    /**
     * Crea un registro
     */
    public function create(array $data, array $fillable = [], array $casts = []): array
    {
        $values = parent::create($data, $fillable, $casts);

        return $this->exec(
            'pgsql',
            $this->model->connection,
            $values
        );
    }

    /**
     * El insert sencillamente realiza un insert de los datos,
     * Sin embargo, no ejecuta los casts ni tiene en cuenta los fillable.
     * Esencialmente es un método potencialmente peligroso.
     * 
     * @return array de igual forma que el método "create", este devuelve los placeholders a insertar.
     */
    public function insert(array $data): array
    {
        $values = parent::insert($data);

        return $this->exec(
            'pgsql',
            $this->model->connection,
            $values
        );
    }
}
