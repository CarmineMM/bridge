<?php

namespace Core\Database\Driver;

class MigratePostgresSQL extends PostgresSQL
{
    /**
     * SQL a generar
     */
    protected string $sql = '{name} {type} {restrict} {default} {key}';

    /**
     * Alter SQL
     */
    private array $alterSql = [];

    /**
     * Genera un primary key
     *
     * @param string $name Nombre de la columna
     * @param bool $null 
     * @return static
     */
    public function bigInt(string $name, bool $null = false): static
    {
        $this->sql .= str_replace(
            ['{name}', '{type}', '{restrict}'],
            [$name, 'BIGINT', $null ? 'NULL' : 'NOT NULL'],
            $this->sql
        );

        return $this;
    }

    /**
     * Permite nulos
     *
     * @return static
     */
    public function nullable(): static
    {
        $this->sql = str_replace(
            ['{restrict}', 'NOT NULL'],
            ['NULL', 'NULL'],
            $this->sql
        );
        return $this;
    }

    /**
     * Agrega primary key a la sentencia
     */
    public function primaryKey(): static
    {
        $this->sql = str_replace(
            ['{key}'],
            ['PRIMARY KEY'],
            $this->sql
        );

        return $this;
    }

    /**
     * ID a partir de un BingInt
     *
     * @return static
     */
    public function id(): static
    {
        $this->bigInt('id');
        $this->sql = trim($this->sql, ',') . ' PRIMARY KEY,';
        return $this;
    }

    /**
     * Genera un varchar
     */
    public function varchar(string $name, int $length = 255, $null = false): static
    {
        $this->sql .= "{$name} VARCHAR({$length}) ";
        $this->sql .= $null ? 'NULL,' : 'NOT NULL,';
        return $this;
    }
}
