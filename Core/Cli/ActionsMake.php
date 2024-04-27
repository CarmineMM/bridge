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
        if ($isHelp) {
            return $this->printHelp(
                Lang::_get('controller.make-description'),
                'php jump make:controller {controller_name}',
                Lang::_get('controller.name')
            );
        }


        if (count($args) < 1) {
            return $this->printArgsRequired(Lang::_get('controller.required-name'));
        }

        $stubHandler = new \Core\Foundation\Stubs\StubHandler();

        try {
            $fileSaved = $stubHandler->publishController($args[0]);

            $this->color_green(
                Lang::_get('controller.created-in', ['folder' => $fileSaved])
            );
        } catch (\Throwable $th) {
            $this->color_red($th->getMessage());
        }

        return $this->toPrint();
    }
}
