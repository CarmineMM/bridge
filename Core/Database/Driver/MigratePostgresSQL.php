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
     * Nombre de la columna
     */
    private string $columnName = '';

    /**
     * Genera un BigInt
     */
    public function bigInt(string $name, bool $null = false): static
    {
        $this->columnName = $name;

        $this->sql = str_replace(
            ['[name]', '[type]', '[restrict]'],
            [$name, 'BIGINT', $null ? 'NULL' : 'NOT NULL'],
            $this->sql
        );

        return $this;
    }

    /**
     * Genera un BigInt
     */
    public function bigSerial(string $name, bool $null = false): static
    {
        $this->columnName = $name;

        $this->sql = str_replace(
            ['[name]', '[type]', '[restrict]'],
            [$name, 'BIGSERIAL', $null ? 'NULL' : 'NOT NULL'],
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
        return $this->bigSerial('id')->primaryKey();
    }

    /**
     * Genera un varchar
     */
    public function string(string $name, int $length = 255, $null = false): static
    {
        $this->columnName = $name;

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

    /**
     * Comentarios sobre la columna
     */
    public function comment(string $comment, string $table_name): static
    {
        $this->alterSql[] = "COMMENT ON COLUMN {$table_name}.{$this->columnName} IS '{$comment}';";
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
}
