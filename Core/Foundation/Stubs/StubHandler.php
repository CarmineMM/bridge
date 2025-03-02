<?php

namespace Core\Foundation\Stubs;

use Core\Cli\Printer;
use Core\Foundation\Filesystem;
use Core\Loaders\Config;
use Core\Support\Str;
use Core\Translate\Lang;

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
                [$name, $strName->lower()->replace(['create', 'table', 'edit'], '')->getString()],
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
     * Publicar un modelo
     */
    public function publishModel(string $name): string
    {
        $getContent = file_get_contents($this->stub_folder . $this->findStub['model']);
        $buildFileName = str_replace('{model_name}', $name, $this->buildFileName['model']);
        $getContent = str_replace('{modelName}', $name, $getContent);
        return $this->createFile($buildFileName, $getContent, 'model');
    }

    /**
     * Crea el documento
     *
     * @param [type] $fileSave
     * @return string Devuelve el archivo creado
     */
    public function createFile(string $buildFileName, string $content, string $resultFolder, bool $customResultFolder = false): string
    {
        if ($customResultFolder) {
            $fileSave = $resultFolder;
        } else {
            $fileSave = Filesystem::rootPath([
                $this->buildResultFolder[$resultFolder],
                $buildFileName
            ]);
        }

        if (file_exists($fileSave)) {
            throw new \Exception("The file already exists: {$fileSave}");
        }

        $file = fopen($fileSave, 'w');

        if ($file) {
            fwrite($file, $content);
        } else {
            (new Printer)
                ->color_red(Lang::_get("console.create-component"))
                ->bg_green()
                ->toPrint();
            if (!file_exists(dirname($fileSave))) {
                mkdir(dirname($fileSave), 0777, true);
            }

            file_put_contents($fileSave, $content);
        }


        return $fileSave;
    }

    /**
     * Crea un full bridge config file
     */
    public function publishConfigFullBridgeFile(): string
    {
        $getContent = file_get_contents($this->stub_folder . $this->findStub['install:fullbridge']);
        $buildFileName =  $this->buildFileName['install:fullbridge'];
        return $this->createFile($buildFileName, $getContent, 'config');
    }

    /**
     * Create bridge component
     */
    public function createBridgeComponent(string $componentName): string
    {
        $namespace = Config::get('fullbridge.namespace', 'App\FullBridge');
        $getContent = file_get_contents($this->stub_folder . $this->findStub['fullbridge:component']);
        $getContent = str_replace(
            ['{component_name}', '{namespace}'],
            [$componentName, $namespace],
            $getContent
        );
        $resultFolder = str_replace('App', 'app', $namespace);
        $buildFileName = str_replace('{component_name}', $componentName, $this->buildFileName['fullbridge:component']);

        return $this->createFile(
            $buildFileName,
            $getContent,
            Filesystem::rootPath([
                $resultFolder,
                $buildFileName
            ]),
            true
        );
    }
}
