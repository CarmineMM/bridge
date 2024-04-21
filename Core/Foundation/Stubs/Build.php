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
    protected array $buildFileName = [
        'migration' => 'Create{table}Table.php',
    ];

    /**
     * Carpeta de destino para los archivos
     */
    protected array $buildResultFolder = [
        'migration' => 'database/migrations',
    ];

    /**
     * Nombre del stub a buscar
     */
    protected array $findStub = [
        'migration' => 'migration.stub',
    ];
}
