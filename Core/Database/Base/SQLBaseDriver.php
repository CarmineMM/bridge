<?php

namespace Core\Database\Base;

use Core\Database\Complement\CarryOut;

class SQLBaseDriver extends CarryOut
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
     * Prepara la instancia del SQL
     *
     * @param string $type
     * @return void
     */
    protected function instance(string $type): void
    {
        if (!$this->sql) {
            $this->sql = str_replace('{table}', $this->table, $this->layout[$type]);
        }
    }

    /**
     * Limita la cantidad de registros a obtener
     */
    public function limit(int $limit): static
    {
        $this->instance('select');

        $this->sql = str_replace('{limit}', "LIMIT {$limit}", $this->sql);

        return $this;
    }

    /**
     * Realiza un where en el sentencia SQL
     */
    public function where($column, $sentence, $three = ''): static
    {
        $this->instance('select');

        $this->sql = $three
            ? str_replace('{where}', "WHERE {$column} {$sentence} {$three}", $this->sql)
            : str_replace('{where}', "WHERE {$column} = '{$sentence}'", $this->sql);

        return $this;
    }
}
