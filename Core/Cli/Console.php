<?php

namespace Core\Cli;

use Core\Foundation\Application;
use Core\Loaders\Config;
use Core\Translate\Lang;

class Console extends Printer
{
    /**
     * Version del CLI
     *
     * @var string
     */
    private $versionCli = '1.0.0';

    /**
     * Servidor a ejecutar
     */
    public string $server = 'http://localhost:8080/';

    /**
     * Comando a ejecutar
     *
     * @var string
     */
    private $command;

    /**
     * Constructor Console
     */
    public function __construct()
    {
        $this->command = $_SERVER['argv'][1] ?? false;
    }

    /**
     * Ejecuta la consola
     *
     * @return string
     */
    public function exec(): string
    {
        if (!$this->command || $this->command === 'list') return $this->list();

        $actions = new Actions(
            server: $this->server
        );

        match ($this->command) {
            'serve' => $actions->serve(),
            'routes' => $actions->routes(),
            default => '',
        };

        return '';
    }

    /**
     * lista de comandos disponibles.
     */
    private function list(): string
    {
        $this->color_light_cyan(
            Config::get('framework.name', Application::FrameworkName) . ' ' . Config::get('framework.version', Application::FrameworkVersion) . "\n"
        );

        # List
        $this->color_light_green('  list');
        $this->color_unset("\t\t" . Lang::_get('console.list') . "\n");

        # Serve
        $this->color_light_green('  serve');
        $this->color_unset("\t\t" .  Lang::_get('console.serve') .  "\n");

        # Listado de rutas
        $this->color_light_green('  routes');
        $this->color_unset("\t" . Lang::_get('console.routes') . "\n");

        return $this->toPrint();
    }
}
