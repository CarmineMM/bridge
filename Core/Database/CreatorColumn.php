<?php

namespace Core\Database;

use Core\Database\Driver\MigratePostgresSQL;

class CreatorColumn
{
    /**
     * SQL a generar
     */
    private string $sql = '{name} {type} {restrictions} {default}';

    /**
     * Constructor
     */
    public function __construct(
        /**
         * Driver
         */
        private MigratePostgresSQL $driver
    ) {
        //...
    }

    /**
     * Crea un Big Int
     */
    public function bigInt(string $name): static
    {
        $this->sql = str_replace(
            ['{name}', '{type}'],
            [$name, 'BIGINT'],
            $this->sql
        );

        return $this;
    }

    /**
     * Primary key a la columna actual
     */
    public function primaryKey(): static
    {
        $this->sql = str_replace(
            ['{restrictions}'],
            ['PRIMARY KEY {restrictions}'],
            $this->sql
        );

        return $this;
    }

    /**
     * Valor por default
     */
    public function default(string|int $default): static
    {
        $this->sql = str_replace(
            ['{default}'],
            ["DEFAULT {$default}"],
            $this->sql
        );

        return $this;
    }
}
