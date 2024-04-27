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
    private string $command;

    /**
     * Argumento extra
     */
    private array $args = [];

    /**
     * Lista de comandos disponibles
     */
    private array $commands = [];

    /**
     * Constructor Console
     */
    public function __construct()
    {
        $this->command = $_SERVER['argv'][1] ?? false;
        $this->args = array_slice($_SERVER['argv'], 2);

        $this->commands = [
            'list' => Lang::_get('console.list'),
            'serve' => Lang::_get('console.serve'),
            'routes' => Lang::_get('console.routes') . "\n",

            'migrate' => Lang::_get('console.migrate'),
            'migrate:rollback' => Lang::_get('console.migrate-rollback') . "\n",

            'make:migration' => Lang::_get('console.make.migration'),
            'make:controller' => Lang::_get('console.make.controller'),
        ];
    }

    /**
     * Ejecuta la consola
     *
     * @return string
     */
    public function exec(): string
    {
        if (!$this->command || $this->command === 'list') return $this->list();

        $isHelp = in_array('--help', $this->args) || in_array('-h', $this->args);

        $actions = new Actions(
            server: $this->server
        );

        match ($this->command) {
            'serve' => $actions->serve($isHelp),
            'routes' => $actions->routes($isHelp),
            'migrate' => $actions->migrate($isHelp),
            'migrate:rollback' => $actions->migrate($isHelp, 'down'),

            'make:migration' => $actions->makeMigration($isHelp, $this->cleanArgsActions()),
            'make:controller' => $actions->makeController($isHelp, $this->cleanArgsActions()),
            default => '',
        };

        return '';
    }

    /**
     * Limpia las actions
     */
    private function cleanArgsActions(): array
    {
        return array_filter($this->args, function ($arg) {
            return strpos($arg, "-") === false;
        });
    }

    /**
     * lista de comandos disponibles.
     */
    private function list(): string
    {
        $this->color_light_cyan(
            Config::get('framework.name', Application::FrameworkName) . ' ' . Config::get('framework.version', Application::FrameworkVersion) . "\n"
        );

        foreach ($this->commands as $command => $help) {
            $this->color_light_green(sprintf("\n  %-17s", $command));
            $this->color_unset($help);
        }

        return $this->toPrint();
    }
}
