<?php

namespace Core\Database\Driver;

class MigratePostgresSQL extends PostgresSQL
{
    /**
     * SQL a generar
     */
    protected string $sql = '[name] [type] [restrict] [default] [restrictionKey]';

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
        $this->sql = str_replace(
            ['[name]', '[type]', '[restrict]'],
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
            ['[restrict]', 'NOT NULL'],
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
            ['[restrictionKey]'],
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
        $this->bigInt('id')->primaryKey();
        return $this;
    }

    /**
     * Genera un varchar
     */
    public function string(string $name, int $length = 255, $null = false): static
    {
        $this->sql = str_replace(
            ['[name]', '[type]', '[restrict]'],
            [$name, "VARCHAR($length)", $null ? 'NULL' : 'NOT NULL'],
            $this->sql
        );

        return $this;
    }

    /**
     * SQL final
     */
    public function toSQL(): string
    {
        return trim(str_replace(
            ['[name]', '[type]', '[restrict]', '[default]', '[restrictionKey]'],
            ['', '', '', '', '', ''],
            $this->sql
        ));
    }

    /**
     * Obtiene las alteraciones al SQL
     */
    public function getAlterSql(): array
    {
        return $this->alterSql;
    }

    /**
     * Restablece el SQL
     */
    public function reset(): static
    {
        $this->sql = '[name] [type] [restrict] [default] [restrictionKey]';
        return $this;
    }
}
