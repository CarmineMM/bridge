<?php

namespace Core\Cli;

use Core\Foundation\Router;
use Core\Loaders\Routes;
use Core\Support\Collection;
use Core\Translate\Lang;

class Actions extends Printer
{
    /**
     * Construct
     *
     * @param string $server
     */
    public function __construct(
        public string $server = 'http://localhost:8080/'
    ) {
        //...
    }

    /**
     * Listado de rutas
     */
    public function routes($isHelp = false): void
    {
        if ($isHelp) {
            $this->printHelp(
                Lang::_get('routes.description'),
                'php jump routes',
                Lang::_get('no-args')
            );
            return;
        }

        $this->color_white("--------------------------------------------------------------------\n");
        $this->color_white("|  Method  |\tURL\t\t|\tName\t|\tAction\t\t|");

        $routes = Collection::make(Router::$routes)->collapse()->toArray();

        foreach ($routes as $route) {
            // Método
            $this->color_white("\n|\t{$route['method']}");

            // Ruta
            $this->color_cyan("| /{$route['url']} \t");

            // Nombre
            $this->color_white("| {$route['name']}\t");

            // Action
            if ($route['callback'] instanceof \Closure) {
                $this->color_green("| Closure\t\t |");
            } else if (is_array($route['callback']) && count($route['callback']) == 1) {
                $this->color_green("| {$route['callback'][0]}:__invoke   \t|");
            } else if (is_array($route['callback']) && count($route['callback']) == 2) {
                $this->color_green("| {$route['callback'][0]}:{$route['callback'][1]}   \t|");
            }
        }

        $this->color_white("\n--------------------------------------------------------------------");

        $this->toPrint();
    }

    /**
     * Ejecuta el servidor local
     */
    public function serve($isHelp = false): void
    {
        if ($isHelp) {
            $this->printHelp(
                Lang::_get('serve.description'),
                'php jump serve',
                Lang::_get('no-args')
            );
            return;
        }

        $this->color_light_cyan(
            Lang::_get('console.server-start-in', ['server' => $this->server], 'Development server')
        )->toPrint();

        $runIn = str_replace('http://', '', $this->server);
        $runIn = str_replace('https://', '', $runIn);

        exec("php -S {$runIn} -t public");
        return;
    }

    /**
     * Imprime una descripción de ayuda
     *
     * @param string $description
     * @param string $command
     * @param string $args
     * @return void
     */
    private function printHelp(string $description, string $command, string $args): void
    {
        $this->color_light_cyan(Lang::_get('description') . "\n");
        $this->color_unset("   " . $description . "\n\n");

        $this->color_light_cyan(Lang::_get('how-to-use') . "\n");
        $this->color_light_green('  ' . $command);

        $this->color_light_cyan("\n\n" .  Lang::_get('args') . ":\n");
        $this->color_red('  ' . $args);
        $this->toPrint();
    }
}
