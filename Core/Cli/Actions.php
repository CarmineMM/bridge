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
    public function routes(bool $isHelp = false): string
    {
        if ($isHelp) {
            $this->printHelp(
                Lang::_get('routes.description'),
                'php jump routes'
            );
            return '';
        }

        // Encabezados de la tabla
        $this->color_light_green(sprintf("%-10s %-30s %-20s %-30s\n", "METHOD", "URL", "NAME", "ACTION"));

        foreach (Router::$routes as $method => $routes) {
            foreach ($routes as $route) {
                $formattedLine = sprintf(
                    "%-10s %-30s %-20s %-30s\n",
                    $method,
                    '/' . $route['url'],
                    $route['name'] ?? '',
                    match (true) {
                        $route['callback'] instanceof \Closure => 'Closure',
                        is_array($route['callback']) && count($route['callback']) == 1 => $route['callback'][0] . ':__invoke',
                        is_array($route['callback']) && count($route['callback']) == 2 => $route['callback'][0] . ':' . $route['callback'][1],
                        default => $route['action'],
                    }
                );
                $this->color_unset($formattedLine);
            }
        }

        return $this->toPrint();
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
     */
    private function printHelp(string $description, string $command, string|bool $args = false): string
    {
        $this->color_light_cyan(Lang::_get('description') . "\n");
        $this->color_unset("   " . $description . "\n\n");

        $this->color_light_cyan(Lang::_get('how-to-use') . "\n");
        $this->color_light_green('  ' . $command);

        $this->color_light_cyan("\n\n" .  Lang::_get('args') . ":\n");

        $args ? $this->color_light_green('  ' . $args) : $this->color_red('  ' . Lang::_get('no-args'));

        return $this->toPrint();
    }

    public function printArgsRequired(string $message): string
    {
        return $this->color_red($message)->toPrint();
    }

    /**
     * Ejecuta las migraciones del sistema
     */
    public function migrate(bool $isHelp, array $args = []): string
    {
        if ($isHelp) {
            return $this->printHelp(
                Lang::_get('migrate.description'),
                'php jump migrate {table}',
                Lang::_get('migrate.name')
            );
        }

        if (count($args) < 1) {
            return $this->printArgsRequired(Lang::_get('migrate.required-name'));
        }

        $stubHandler = new \Core\Foundation\Stubs\StubHandler();

        $this->color_green(
            Lang::_get('migrate.created-in', ['folder' => $stubHandler->publishMigration($args[0])])
        );

        return $this->toPrint();
    }
}
