<?php

namespace Core\Foundation\Stubs;

use Core\Foundation\Filesystem;

class StubHandler extends Build
{
    /**
     * Construct
     */
    public function __construct(
        public string $stub_folder = __DIR__ . '/../../stubs/'
    ) {
        //...
    }

    /**
     * Publica una migración
     */
    public function publishMigration(string $name): string
    {
        $getContent = file_get_contents($this->stub_folder . $this->findStub['migration']);

        // Si no contiene un piso abajo, es que el usuario esta determinando el nombre de la tabla
        if (str_contains($name, 'Create') || str_contains($name, 'Edit')) {
            $name = $name;
        } else {
            // $name = str_replace(['{time}', '{table}'], [$time, $name], $this->buildFileName['migration']);
        }

        $fileSave = Filesystem::rootPath([
            $this->buildResultFolder['migration'],
            $name . '.php'
        ]);

        $file = fopen($fileSave, 'w');

        fwrite($file, $getContent);

        return $fileSave;
    }

    /**
     * Crea el documento
     *
     * @param [type] $fileSave
     * @return string Devuelve el archivo creado
     */
    public function createFile(string $fileSave): void
    {
    }
}
