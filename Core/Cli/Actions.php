<?php

namespace Core\Cli;

use Core\Database\MigrationHandler;
use Core\Foundation\RateLimit\RateLimit;
use Core\Foundation\Router;
use Core\Loaders\Config;
use Core\Loaders\Routes;
use Core\Support\Collection;
use Core\Translate\Lang;

class Actions extends Printer
{
    use ActionsMake;

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
     * Ejecuta las migraciones
     *
     * @param boolean $isHelp
     * @return string
     */
    public function migrate(bool $isHelp, $type = 'up'): string
    {
        if ($isHelp) {
            return $this->printHelp(
                $type === 'up' ? Lang::_get('migrate.description') :  Lang::_get('migrate.description-rollback'),
                $type === 'up' ? 'php jump migrate' : 'php jump migrate:rollback',
            );
        }

        $class = 'Database\Migrator';

        if (class_exists($class)) {
            $handler = new $class;
        } else {
            $handler = new MigrationHandler;
        }

        $handler->getMigrationsFiles();
        $handler->runQueries($type, $this);

        return '';
    }

    /**
     * Lista los elementos que fueron afectados por el rate limit
     *
     * @param boolean $isHelp
     * @return string
     */
    public function rateLimitList(bool $isHelp): string
    {
        foreach (RateLimit::list(Config::get('security.rate_limit.driver', 'session')) as $rateLimit) {
            dump($rateLimit);
        }
        return '';
    }

    /**
     * Lista los elementos que fueron afectados por el rate limit
     *
     * @param boolean $isHelp
     * @return string
     */
    public function resetRateLimit(bool $isHelp): string
    {
        if ($isHelp) {
            return $this->printHelp(
                Lang::_get('rate-limit.reset'),
                'php jump rate-limit:reset',
            );
        }

        RateLimit::reset(
            Config::get('security.rate_limit.driver', 'session')
        );

        return '';
    }
}
