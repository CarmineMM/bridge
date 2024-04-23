<?php

namespace Core\Database;

use Core\Database\Driver\MigratePostgresSQL;

class CreatorColumn
{
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
        $this->driver->bigInt($name);
        return $this;
    }

    /**
     * Permite nulos
     */
    public function nullable(): static
    {
        $this->driver->nullable();
        return $this;
    }

    /**
     * Agregar primary key
     */
    public function primaryKey(): static
    {
        $this->driver->primaryKey();
        return $this;
    }

    /**
     * ID en bigInt
     */
    public function id(): static
    {
        $this->driver->bigInt('id')->primaryKey();
        return $this;
    }

    /**
     * Crea un Varchar
     */
    public function string(string $name, int $length = 255, $null = false): static
    {
        $this->driver->string($name, $length, $null);
        return $this;
    }

    /**
     * Obtiene el creator column
     */
    public function _get(): string
    {
        $sql = $this->driver->toSQL();
        $this->driver->reset();
        return $sql;
    }

    /**
     * Alteraciones al SQL
     */
    public function getAlterSql(): array
    {
        return $this->driver->getAlterSql();
    }
}
