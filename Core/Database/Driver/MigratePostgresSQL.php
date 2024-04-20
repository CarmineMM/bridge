<?php

namespace Core\Database\Driver;

class MigratePostgresSQL extends PostgresSQL
{
    /**
     * Genera un primary key
     *
     * @param string $column
     * @return static
     */
    public function bigInt(string $name, $null = false): static
    {
        $this->sql .= "{$name} BIGINT ";
        $this->sql .= $null ? 'NULL,' : 'NOT NULL,';

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
