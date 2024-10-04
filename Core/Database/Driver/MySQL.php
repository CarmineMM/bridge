<?php

namespace Core\Database\Driver;

use Core\Database\Base\SQLBaseDriver;
use Core\Database\Model;
use Core\Implements\DatabaseDriver;

class MySQL extends SQLBaseDriver implements DatabaseDriver
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
        $dsn = "mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']}";

        try {
            $this->pdo = new \PDO($dsn, $this->config['username'], $this->config['password'], $this->config['options'] ?? []);
        } catch (\PDOException $th) {
            throw new \Exception("Bridge Driver MySQL/MariaDB: " . $th->getMessage(), 500);
        }

        $this->model = $model;
    }

    /**
     * Obtiene todos los registros de la tabla
     */
    public function all(array $columns = ['*']): array
    {
        $this->sql = str_replace('{table}', $this->model->getTable(), $this->layout['select']);

        $this->columns = $columns;

        return $this->exec(
            'mysql',
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
}
