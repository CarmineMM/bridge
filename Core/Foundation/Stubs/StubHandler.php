<?php

namespace Core\Foundation\Stubs;

use Core\Foundation\Filesystem;
use Core\Support\Str;

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
        $buildFileName = $this->buildFileName['migration'];
        $strName = new Str($name);

        // Esto entra aca si el usuario esta colocando manualmente el nombre
        if ($strName->contains(['Create', 'Edit'])) {
            $name = $strName->getString();
            $buildFileName = $name . '.php';
            $getContent = str_replace(
                ['{className}', '{table}'],
                [$name, $strName->lower()->replace(['create', 'table', 'edit'], '')->pluralize()->getString()],
                $getContent
            );
        } else {
            $strName->upperFirst();
            $buildFileName = str_replace('{table}', $strName->getString(), $buildFileName);
            $getContent = str_replace(
                ['{className}', '{table}'],
                [str_replace('.php', '', $buildFileName), $strName->lower()->getString()],
                $getContent
            );
        }

        return $this->createFile($buildFileName, $getContent, 'migrations');
    }

    /**
     * Publish controller
     */
    public function publishController(string $name): string
    {
        $getContent = file_get_contents($this->stub_folder . $this->findStub['controller']);
        $buildFileName = str_replace('{controller_name}', $name, $this->buildFileName['controller']);
        $getContent = str_replace('{controller_name}', $name, $getContent);

        return $this->createFile($buildFileName, $getContent, 'controller');
    }

    /**
     * Crea el documento
     *
     * @param [type] $fileSave
     * @return string Devuelve el archivo creado
     */
    public function createFile(string $buildFileName, string $content, string $resultFolder): string
    {
        $fileSave = Filesystem::rootPath([
            $this->buildResultFolder[$resultFolder],
            $buildFileName
        ]);

        if (file_exists($fileSave)) {
            throw new \Exception("The file already exists: {$fileSave}");
        }

        $file = fopen($fileSave, 'w');

        fwrite($file, $content);

        return $fileSave;
    }
}
