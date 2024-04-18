<?php

namespace Core\Database\Driver;

use Core\Database\Base\SQLBaseDriver;
use Core\Database\Complement\CarryOut;
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
        public Model $model
    ) {
        $dsn = "pgsql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']}";

        try {
            $this->pdo = new \PDO($dsn, $this->config['username'], $this->config['password']);
        } catch (\PDOException $th) {
            throw new \Exception("Error connect to database: " . $th->getMessage(), 500);
        }

        $this->table = $this->model->getTable();
    }

    /**
     * Obtiene todos los registros de la tabla
     */
    public function all(array $columns = ['*']): array
    {
        $this->sql = str_replace('{table}', $this->table, $this->layout['select']);

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
        $this->columns = $columns;

        return $this->exec(
            'pgsql',
            $this->model->connection
        );
    }

    /**
     * Ejecutar query SQL
     */
    public function excQuery(string $query): array
    {
        $this->sql = $query;

        return $this->exec(
            'manually',
            $this->model->connection
        );
    }
}
