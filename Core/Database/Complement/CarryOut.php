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

        // $this->pdo->beginTransaction();

        if (strpos($this->sql, 'SELECT') !== false) {
            $this->prepareSelect();
        }

        $query = $this->pdo->prepare($this->sql);
        $data = [];

        try {
            $query->execute($params);
        } catch (\Throwable $th) {
            // $this->pdo->rollBack();
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
            $data = [
                $this->model->getPrimaryKey() => $this->pdo->lastInsertId(
                    $this->model->getPrimaryKey()
                ),
                ...$this->data,
            ];
        }

        // Debug
        if (Config::get('app.debug', false)) {
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
    private function prepareSelect(): void
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
}
