<?php

namespace Core\Database\Base;

use Core\Database\Complement\CarryOut;
use Core\Database\Complement\Casts;

class SQLBaseDriver extends CarryOut
{
    use Casts;

    /**
     * Layouts para las query's
     *
     * @var array
     */
    protected array $layout = [
        'select' => 'SELECT {column} {innerQuery} FROM {table} {where} {group} {order} {limit} {offset}',
        'insert' => 'INSERT INTO {table} ({keys}) VALUES ({values})',
        //'update' => 'UPDATE %s SET %s',
        'delete' => 'DELETE FROM {table} {where}',
    ];

    /**
     * Prepara la instancia del SQL
     *
     * @param string $type
     * @return void
     */
    protected function instance(string $type = ''): void
    {
        $this->sql = str_replace('{table}', $this->model->getTable(), $this->layout[$type]);
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
    public function where(string $column, string $sentence, string $three = ''): static
    {
        foreach ($this->layout as $key => $value) {
            $this->layout[$key] = $three
                ? str_replace('{where}', "WHERE {$column} {$sentence} {$three} {where}", $value)
                : str_replace('{where}', "WHERE {$column} = '{$sentence}' {where}", $value);
        }

        return $this;
    }

    /**
     * Realiza un 'or where' en el sentencia SQL
     */
    public function orWhere(string $column, string $sentence, string $three = ''): static
    {
        $this->sql = $three
            ? str_replace('{where}', "OR WHERE {$column} {$sentence} {$three} {where}", $this->sql)
            : str_replace('{where}', "OR WHERE {$column} = '{$sentence}' {where}", $this->sql);

        return $this;
    }

    /**
     * Crear un nuevo registro
     */
    public function create(array $data, array $fillable = [], array $casts = []): array
    {
        $this->instance('insert');

        $keys = [];
        $values = [];
        $placeholders = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $fillable)) {
                $keys[] = $key;
                $values[] = "'" . $this->getApplyCast($casts[$key] ?? null, $value, 'set') . "'";
                $placeholders[] = '?';
            }
        }

        $this->sql = str_replace('{keys}', implode(', ', $keys), $this->sql);
        $this->sql = str_replace('{values}', implode(', ', $placeholders), $this->sql);

        return $values;
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
        $this->instance('insert');

        $keys = [];
        $values = [];
        $placeholders = [];

        foreach ($data as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
            $placeholders[] = '?';
        }

        $this->sql = str_replace('{keys}', implode(', ', $keys), $this->sql);
        $this->sql = str_replace('{values}', implode(', ', $placeholders), $this->sql);

        return $values;
    }
}
