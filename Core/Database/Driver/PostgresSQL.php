<?php

namespace Core\Database\Driver;

use Core\Database\Complement\CarryOut;
use Core\Implements\DatabaseDriver;

/**
 * Driver para PostgreSQL
 */
class PostgresSQL extends CarryOut implements DatabaseDriver
{
    /**
     * Layouts para las query's
     *
     * @var array
     */
    protected array $layout = [
        'select' => 'SELECT {column} {innerQuery} FROM {table} {where} {group} {order} {limit} {offset}',
        //'insert' => 'INSERT INTO %s (%s) VALUES (%s)',
        //'update' => 'UPDATE %s SET %s',
        //'delete' => 'DELETE FROM %s',
    ];

    /**
     * Constructor
     */
    public function __construct(
        public array $config
    ) {
        $dsn = "pgsql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['database']}";

        try {
            $this->pdo = new \PDO($dsn, $this->config['username'], $this->config['password']);
        } catch (\PDOException $th) {
            throw new \Exception("Error connect to database: " . $th->getMessage());
        }
    }

    /**
     * Obtiene todos los registros de la tabla
     */
    public function all(string $table, array $columns = ['*']): array
    {
        $this->sql = str_replace('{table}', $table, $this->layout['select']);
        $this->columns = $columns;
        return $this->exec();
    }
}
