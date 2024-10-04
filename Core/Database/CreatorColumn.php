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
        $this->driver->bigSerial('id')->primaryKey();
        return $this;
    }

    /**
     * Crea un Varchar
     */
    public function string(string $name, int $length = 255): static
    {
        $this->driver->string($name, $length);
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
    public function bigSerial(string $name): static
    {
        $this->driver?->bigSerial($name);
        return $this;
    }

    /**
     * Agrega un campo de tipo timestamp
     */
    public function timestamp(string $name): static
    {
        $this->driver?->timestamp($name);
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

    /**
     * Asigna un default
     */
    public function required(): static
    {
        $this->driver?->required();
        return $this;
    }

    /**
     * Text Column
     */
    public function text(string $name): static
    {
        $this->driver?->text($name);
        return $this;
    }

    /**
     * Date Column
     */
    public function date(string $name): static
    {
        $this->driver?->date($name);
        return $this;
    }

    /**
     * Time Column
     */
    public function time(string $name): static
    {
        $this->driver?->time($name);
        return $this;
    }

    /**
     * Json column
     */
    public function json(string $name): static
    {
        $this->driver?->json($name);
        return $this;
    }

    /**
     * Boolean column
     */
    public function boolean(string $name): static
    {
        $this->driver?->boolean($name);
        return $this;
    }

    /**
     * Integer column
     */
    public function integer(string $name, bool $unsigned): static
    {
        $this->driver?->integer($name, $unsigned);
        return $this;
    }

    /**
     * Unique column
     */
    public function unique(): static
    {
        $this->driver?->unique();
        return $this;
    }
}
