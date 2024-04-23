<?php

namespace Core\Implements;

interface MigrateTable
{
    /**
     * Indica la acción a realizar en la migración
     */
    public function up(): MigrateTable;

    /**
     * Indica la acción al ejecutar rollback´s
     */
    public function down(): MigrateTable;
}
