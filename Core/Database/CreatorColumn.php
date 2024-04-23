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
        private MigratePostgresSQL $driver,
        /**
         * Nombre de la tabla sobre la que se crean las columnas
         */
        private string $table_name = ''
    ) {
        //...
    }

    /**
     * Establece el nombre de la tabla
     *
     * @param string $table
     * @return void
     */
    public function setTableName(string $table): void
    {
        $this->table_name = $table;
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

    /**
     * Comentario sobre la columna
     */
    public function comment(string $comment): static
    {
        $this->driver?->comment($comment, $this->table_name);
        return $this;
    }

    /**
     * BigSerial. (Solo disponible para PostgresSQL).
     */
    public function bigSerial(string $name, bool $null = false): static
    {
        $this->driver?->bigSerial($name, $null);
        return $this;
    }

    /**
     * Agrega un campo de tipo timestamp
     */
    public function timestamp(string $name, bool $null = false): static
    {
        $this->driver?->timestamp($name, $null);
        return $this;
    }

    /**
     * Agrega un campo de tipo timestamp
     */
    public function timestamps(string $created_at = 'created_at', string $updated_at = 'updated_at'): static
    {
        $this->driver?->timestamps($created_at, $updated_at);
        return $this;
    }

    /**
     * Asigna un default
     */
    public function default(string|int $default): static
    {
        $this->driver?->default($default);
        return $this;
    }
}
