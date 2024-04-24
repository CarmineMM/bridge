<?php

namespace Core\Database\Complement;

use Core\Database\Model;
use Core\Foundation\Context;
use Core\Loaders\Config;

class CarryOut
{
    /**
     * Columnas para la petición del SELECT
     */
    protected array $columns = ['*'];

    /**
     * SQL Generado
     */
    protected string $sql = '';

    /**
     * Conexión por PDO
     *
     * @var \PDO
     */
    protected \PDO $pdo;

    /**
     * Modelo
     *
     * @var Model
     */
    protected Model $model;

    /**
     * Es la data a salvar (UPDATE/CREATE/DELETE)
     */
    protected array $data = [];

    /**
     * Ejecutar la consulta
     */
    protected function exec(string $driver = '', string $connection = '', array $params = []): array
    {
        if (Config::get('app.debug', false)) {
            $startTime = microtime(true);
            $startMemory = memory_get_usage();
        }

        $this->prepareSql();

        $query = $this->pdo->prepare($this->sql);
        $data = [];

        try {
            $query->execute($params);
        } catch (\Throwable $th) {
            // 0. El tipo del error
            // 1. Código del error
            // 2. Mensaje del error
            throw new \Exception("Bridge ORM: " . $query->errorInfo()[2], 500, $th);
        }

        // Devolver resultado del SELECT
        if (strpos($this->sql, 'SELECT') !== false) {
            $data = $query->fetchAll(\PDO::FETCH_ASSOC);
        }

        // Insert
        if (strpos($this->sql, 'INSERT') !== false) {
            $data = $this->data;

            if (!Config::get('framework.consoleMode', false)) {
                $data[$this->model->getPrimaryKey()] = $this->pdo->lastInsertId();
            }
        }

        // DELETE
        if (strpos($this->sql, 'DELETE') !== false) {
            if (!Config::get('framework.consoleMode', false)) {
                $data[$this->model->getPrimaryKey()] = $this->pdo->lastInsertId();
            }
        }

        // Debug
        if (Config::get('app.debug', false) && !Config::get('framework.consoleMode', false)) {
            $context = new Context;
            $context->setState(
                'bridge:query',
                array_merge(
                    $context->getState('bridge:query', []),
                    [
                        [
                            'query' => $this->sql,
                            'time' => microtime(true) - $startTime,
                            'memory' => memory_get_usage() - $startMemory,
                            'driver' => $driver,
                            'connection' => $connection,
                        ],
                    ],
                )
            );
        }

        $this->sql = '';

        return $data;
    }

    /**
     * Prepara el Select
     *
     * @return void
     */
    private function prepareSql(): void
    {
        $columns = implode(', ', $this->columns);
        $this->sql = str_replace('{column}', $columns, $this->sql);
        $this->sql = trim(
            str_replace(
                ['{innerQuery}', '{where}', '{group}', '{order}', '{limit}', '{offset}'],
                ['', '', '', '', '', ''],
                $this->sql
            )
        );
    }

    /**
     * Obtiene el SQL generado
     */
    public function toSQL(): string
    {
        return $this->sql;
    }

    /**
     * Restablece el SQL
     */
    public function reset(): static
    {
        $this->sql = '';
        return $this;
    }

    /**
     * Ejecuta una query manualmente
     *
     * @param string $query
     * @return mixed El retorno puede estar condicionado a la query requerida
     */
    public function query(string $query): mixed
    {
        $this->sql = $query;
        return $this->exec('manually', $this->model->connection, []);
    }
}
