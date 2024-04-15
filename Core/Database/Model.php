<?php

namespace Core\Database;

class Model
{
    /**
     * Tabla en la base de datos
     */
    protected string $table;

    /**
     * Llave primaria de la tabla
     */
    protected string $primaryKey = 'id';

    /**
     * Columnas que se pueden editar y crear
     */
    protected array $fillable = [];
}
