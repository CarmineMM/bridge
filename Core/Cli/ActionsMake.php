<?php

namespace Core\Cli;

use Core\Translate\Lang;

trait ActionsMake
{
    /**
     * Ejecuta las migraciones del sistema
     */
    public function makeMigration(bool $isHelp, array $args = []): string
    {
        if ($isHelp) {
            return $this->printHelp(
                Lang::_get('migrate.make-description'),
                'php jump make:migration {table}',
                Lang::_get('migrate.name')
            );
        }

        if (count($args) < 1) {
            return $this->printArgsRequired(Lang::_get('migrate.required-name'));
        }

        $stubHandler = new \Core\Foundation\Stubs\StubHandler();

        try {
            $fileSaved = $stubHandler->publishMigration($args[0]);

            $this->color_green(
                Lang::_get('migrate.created-in', ['folder' => $fileSaved])
            );
        } catch (\Throwable $th) {
            $this->color_red($th->getMessage());
        }

        return $this->toPrint();
    }

    /**
     * Crea un nuevo controlador
     */
    public function makeController(bool $isHelp, array $args = []): string
    {
        return $this->autoMake($isHelp, $args, 'controller', 'publishController');
    }

    /**
     * Auto make, hecho para los archivos que solo requieren un nombre y ya.
     * Como los controladores, modelos, etc.
     * 
     * @param bool $isHelp Bool de conseguir ayuda del comando
     * @param array $args Argumentos del comando
     * @param string $getFor El stub de donde viene el controller
     * @param string $call Ruta del controlador
     */
    private function autoMake(bool $isHelp, array $args = [], string $getFor, string $call): string
    {
        if ($isHelp) {
            return $this->printHelp(
                Lang::_get("{$getFor}.make-description"),
                "php jump make:{$getFor} {name}",
                Lang::_get("{$getFor}.name")
            );
        }


        if (count($args) < 1) {
            return $this->printArgsRequired(Lang::_get("{$getFor}.required-name"));
        }

        $stubHandler = new \Core\Foundation\Stubs\StubHandler();

        try {
            $fileSaved = $stubHandler->{$call}($args[0]);

            $this->color_green(
                Lang::_get("{$getFor}.created-in", ['folder' => $fileSaved])
            );
        } catch (\Throwable $th) {
            $this->color_red($th->getMessage());
        }

        return $this->toPrint();
    }

    /**
     * Crea un nuevo modelos
     */
    public function makeModel(bool $isHelp, array $args = []): string
    {
        return $this->autoMake($isHelp, $args, 'model', 'publishModel');
    }

    /**
     * Instala fullbridge
     */
    public function fullBridgeInstall(bool $isHelp): string
    {
        if ($isHelp) {
            return $this->printHelp(
                Lang::_get("full-bridge.install"),
                "php jump install:fullbridge",
            );
        }

        return $this->autoMake($isHelp, [0], 'full-bridge', 'publishConfigFullBridgeFile');
    }
}
