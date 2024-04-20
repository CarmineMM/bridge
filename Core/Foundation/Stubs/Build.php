<?php

namespace Core\Foundation\Stubs;

class Build
{
    /**
     * Nombre de archivos a construir,
     * 
     * a. {time} Timestamp
     * b. {table} Nombre de la tabla
     */
    private array $buildFileName = [
        'migration' => '{time}_create_{table}_table.php',
    ];

    /**
     * Nombre del stub a buscar
     */
    private array $findStub = [
        'migration' => 'migration.stub',
    ];
}
