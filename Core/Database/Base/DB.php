<?php

namespace Core\Database\Base;

use Core\Database\Model;

class DB extends Model
{
    /**
     * Construct
     */
    public function __construct(
        /**
         * Configuración de conexión a la base de datos,
         * almacenado en las configuraciones.
         */
        ?string $connection = null,
        /**
         * Colocar el nombre de la tabla
         */
        string $table_name = ''
    ) {
        parent::__construct($connection);
        $this->table = $table_name ? $table_name : $this->table;
    }

    /**
     * Make a DB instance
     */
    public static function make(
        string $table_name = '',
        ?string $connection = null
    ): static {
        return new static($connection, $table_name);
    }
}
