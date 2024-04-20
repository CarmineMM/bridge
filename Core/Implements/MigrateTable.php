<?php

namespace Core\Implements;

interface MigrateTable
{
    /**
     * Indica la acción a realizar en la migración
     */
    public function up(): void;

    /**
     * Indica la acción al ejecutar rollback´s
     */
    public function down(): void;
}
