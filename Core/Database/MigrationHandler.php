<?php

namespace Core\Database;

class MigrationHandler
{
    /**
     * Indica si se debe crear la base de datos si no existe.
     */
    protected $create_database_if_not_exists = true;

    /**
     * Ejecuta las Queries de migraciones
     */
    private function runQueries()
    {
    }
}
