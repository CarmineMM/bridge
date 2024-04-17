<?php

namespace Core\Foundation;

use Core\Exception\ExceptionHandle;
use Core\Loaders\Config;
use Core\Loaders\Routes;
use Core\Support\Collection;
use Core\Support\Debug;
use Core\Support\Env;
use Core\Translate\Lang;
use Core\Translate\Translate;

/**
 * Foundation Application
 *
 * @author Carmine Maggio <carminemaggiom@gmail.com> 
 * @package Bridge Framework
 * @version 1.0.0
 */
class Application
{
    /**
     * Revisa el tiempo de ejecución de la App
     */
    public float $start_time = 0;

    /**
     * Memoria usada en la app
     */
    public int $memory = 0;

    /**
     * Framework version
     */
    const FrameworkVersion = '1.0.0';

    /**
     * Framework name
     */
    const FrameworkName = 'Bridge';

    /**
     * Providers
     */
    private array $providers = [];

    /**
     * Construct
     */
    public function __construct($consoleMode = false)
    {
        [$timer, $memory] = Kernel::initConstants();
        Kernel::loadFunctionsFile();
        Env::load();
        Config::load();

        if (Config::get('app.debug', false)) {
            // Inicia la medición del tiempo
            $this->start_time = $timer;
            $this->memory = $memory;
            Debug::registerRoutes();
        } else {
            unset($timer);
        }

        // Llamar el 'register' de los servicios
        $this->providers = Kernel::registerServiceProviders($consoleMode);

        if (!$consoleMode) {
            Request::make();
            Response::make();
        }

        Translate::make();
        Routes::loadForm();
        Routes::loadForm('api');

        if ($consoleMode) {
            // Actualiza el console mode para identificar el entorno de ejecución
            Config::all()->map(function ($config, $key) {
                if ($key === 'framework') {
                    $config['consoleMode'] = true;
                }

                return $config;
            });
        }
    }

    /**
     * Run the application bridge
     * 
     * @lifecycle 0: Run Application
     */
    public static function run($isConsole = false): mixed
    {
        $app = new static($isConsole);

        if (!$isConsole) {
            $route = $app->coincidenceRoute();

            $through = new CarryThrough($route);

            try {
                if (empty($route)) {
                    throw new \Exception('Route not found', 404);
                }

                $render = $app->runRender($through);

                Response::send();

                echo $render;
            } catch (\Throwable $th) {
                ExceptionHandle::isHttpExceptions($th, $through);
            }
        }

        return '';
    }

    /**
     * Ejecutar el renderizado de la aplicación
     *
     * @param CarryThrough $through
     * @return string
     */
    public function runRender(CarryThrough $through): string
    {
        Security::roadmap($through);

        // Si la ruta existe, entonces ejecutar el boot de los service providers
        if (!empty($route)) {
            Kernel::runBootServiceProvider($this->providers);
        }

        if ((is_array($through->toRender) || is_object($through->toRender) || $through->toRender instanceof Collection)) {
            return $through->renderJson();
        } else {
            $renderHtml = '';

            if (is_string($through->toRender)) {
                $renderHtml = $through->renderString();
            }

            if (Config::get('app.debug', false) && Response::headerIs('Content-Type', 'text/html')) {
                Debugging::renderDebugBar($this, $renderHtml);
            }

            return $renderHtml;
        }
    }

    /**
     * Busca la ruta coincidente de la ruta actual
     * 
     * @lifecycle 8: Route Coincidence
     */
    private function coincidenceRoute(): array
    {
        $request = Request::make();
        $theRoute = [];
        $uri = $request->uri;

        // Obtener la ruta coincidente
        foreach (Router::$routes[$request->method] as $route) {
            $route['path'] = $route['url'];

            // Parámetros de la ruta
            if (strpos($route['url'], ':') !== false) {
                $route['url'] = preg_replace('#:[a-zA-Z0-9]+#', '([a-zA-Z0-9]+)', $route['url']);
            }

            // Ruta coincidente directa
            // Rutas con posibles parámetros
            if (preg_match("#^{$route['url']}$#", $uri, $matches)) {
                $theRoute = $route;
                $theRoute['dynamic_params'] = array_slice($matches, 1);

                $newParams = [];
                $paramIndex = 0;

                // Mapear los parámetros dinámicos
                foreach (explode('/', $theRoute['path']) as $component) {
                    if (strpos($component, ':') === 0) { // Si el componente es un parámetro
                        $paramName = substr($component, 1); // Elimina el ':' del inicio
                        $newParams[$paramName] = $theRoute['dynamic_params'][$paramIndex];
                        $paramIndex++;
                    }
                }

                $theRoute['params'] = $newParams;
                // $theRoute['uri'] = $uri;
                $theRoute['url'] = $theRoute['path'];

                $request->setRoute($theRoute);
                unset($paramIndex);
                break;
            }
        }

        return $theRoute;
    }
}
