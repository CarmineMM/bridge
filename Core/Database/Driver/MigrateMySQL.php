<?php

namespace Core\Database\Driver;

/**
 * Creador de migraciones para MySQL
 * 
 * @author Carmine Maggio <carminemaggiom@gmail.com>
 * @version 1.0.0
 */
class MigrateMySQL extends MySQL
{
    /**
     * SQL a generar
     */
    protected string $sql = '[name] [type] [restrict] [default] [restrictionKey] [comment]';

    /**
     * Alter SQL
     */
    private array $alterSql = [];

    /**
     * Nombre de la columna
     */
    private string $columnName = '';

    /**
     * Genera un BigInt
     */
    public function bigInt(string $name): static
    {
        $this->columnName = $name;

        $this->sql = str_replace(
            ['[name]', '[type]'],
            [$name, 'BIGINT'],
            $this->sql
        );

        return $this;
    }

    /**
     * Auto increment column
     */
    public function autoincrement(): static
    {
        $this->sql = str_replace(
            ['[restrict]'],
            ['AUTO_INCREMENT'],
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
     * Permite nulos
     *
     * @return static
     */
    public function required(): static
    {
        $this->sql = str_replace(
            ['[restrict]'],
            ['NOT NULL'],
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
        return $this->bigInt('id')->autoincrement()->primaryKey();
    }

    /**
     * Genera un varchar
     */
    public function string(string $name, int $length = 255): static
    {
        $this->columnName = $name;

        $this->sql = str_replace(
            ['[name]', '[type]'],
            [$name, "VARCHAR($length)"],
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
            ['[name]', '[type]', '[default]', '[restrictionKey]', '[restrict]', '[comment]'],
            ['', '', '', '', '', '', '', ''],
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

    /**
     * Comentarios sobre la columna
     */
    public function comment(string $comment, string $table_name): static
    {
        $this->sql = str_replace(
            ['[comment]'],
            ["COMMENT $comment"],
            $this->sql
        );
        return $this;
    }

    /**
     * Establece un valor por default
     */
    public function default(string|int $default): static
    {
        $this->sql = str_replace(
            ['[default]'],
            ["DEFAULT $default"],
            $this->sql
        );

        return $this;
    }

    /**
     * Crea un campo timestamp
     */
    public function timestamp(string $name): static
    {
        $this->columnName = $name;

        $this->sql = str_replace(
            ['[name]', '[type]'],
            [$name, 'TIMESTAMP'],
            $this->sql
        );

        return $this;
    }

    /**
     * Column text
     */
    public function text(string $name): static
    {
        $this->columnName = $name;

        $this->sql = str_replace(
            ['[name]', '[type]'],
            [$name, 'TEXT'],
            $this->sql
        );

        return $this;
    }

    /**
     * Date column
     */
    public function date(string $name): static
    {
        $this->columnName = $name;

        $this->sql = str_replace(
            ['[name]', '[type]'],
            [$name, 'DATE'],
            $this->sql
        );

        return $this;
    }

    /**
     * Column Time
     */
    public function time(string $name): static
    {
        $this->columnName = $name;

        $this->sql = str_replace(
            ['[name]', '[type]'],
            [$name, 'TIME'],
            $this->sql
        );

        return $this;
    }

    /**
     * Json Column
     */
    public function json(string $name): static
    {
        $this->columnName = $name;

        $this->sql = str_replace(
            ['[name]', '[type]'],
            [$name, 'JSON'],
            $this->sql
        );

        return $this;
    }

    /**
     * Boolean column
     */
    public function boolean(string $name): static
    {
        $this->columnName = $name;

        $this->sql = str_replace(
            ['[name]', '[type]'],
            [$name, 'BOOLEAN'],
            $this->sql
        );

        return $this;
    }

    /**
     * Integer column
     */
    public function integer(string $name, bool $unsigned = false): static
    {
        $this->columnName = $name;

        $this->sql = str_replace(
            ['[name]', '[type]'],
            [$name, 'INTEGER'],
            $this->sql
        );

        if ($unsigned) {
            $this->sql = str_replace(
                ['[restrict]'],
                [" CHECK ({$name} >= 0)"],
                $this->sql
            );
        }

        return $this;
    }

    /**
     * Created at y updated at
     */
    public function timestamps(string $created_at = 'created_at', string $updated_at = 'updated_at'): static
    {
        if (!empty($created_at)) {
            $this->timestamp($created_at, false)->default('CURRENT_TIMESTAMP');
        }

        if (!empty($updated_at)) {
            $this->timestamp($updated_at, false)->default('CURRENT_TIMESTAMP');
        }

        return $this;
    }

    /**
     * Ejecutar la query, (Espacio inseguro, no usar en producción)
     */
    public function runQuery(string $sql): void
    {
        try {
            $this->pdo->exec($sql);
        } catch (\Throwable $th) {
            throw new \Exception("The table could not be created: {$th->getMessage()}", 500);
        }
    }

    /**
     * Constraint Unique
     *
     * @return static
     */
    public function unique(): static
    {
        $this->sql = str_replace(
            ['[restrictionKey]'],
            ["UNIQUE"],
            $this->sql
        );

        return $this;
    }
}
