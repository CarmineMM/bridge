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
        'controller' => '{controller_name}.php',
    ];

    /**
     * Carpeta de destino para los archivos
     */
    protected array $buildResultFolder = [
        'migrations'  => 'database/migrations',
        'controller' => 'app/Controllers',
    ];

    /**
     * Nombre del stub a buscar
     */
    protected array $findStub = [
        'migration'  => 'migration.stub',
        'controller' => 'controller.stub',
    ];
}
