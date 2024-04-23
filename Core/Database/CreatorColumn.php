<?php

namespace Core\Database;

use Core\Database\Driver\MigratePostgresSQL;

class CreatorColumn
{
    /**
     * Creator
     */
    private MigratePostgresSQL $creator;

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
        $this->creator = $this->driver->bigInt($name);
        return $this;
    }

    /**
     * Permite nulos
     */
    public function nullable(): static
    {
        $this->creator = $this->driver->nullable();
        return $this;
    }

    /**
     * Agregar primary key
     */
    public function primaryKey(): static
    {
        $this->creator = $this->driver->primaryKey();
        return $this;
    }

    /**
     * Obtiene el creator column
     *
     * @return MigratePostgresSQL
     */
    public function _get(): MigratePostgresSQL
    {
        return $this->creator;
    }
}
