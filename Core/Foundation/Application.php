<?php

namespace Core\Foundation;

use Core\Exception\ExceptionHandler;
use Core\Foundation\RateLimit\RateLimit;
use Core\Loaders\Config;
use Core\Loaders\Routes;
use Core\Middleware\MiddlewareHandler;
use Core\Support\Collection;
use Core\Support\Debug;
use Core\Support\Env;
use Core\Translate\Translate;
use Exception;

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
     * Si se esta en modo debug
     *
     * @var boolean
     */
    public bool $isDebug = true;

    /**
     * Construct
     */
    public function __construct($consoleMode = false)
    {
        ExceptionHandler::saveWarnings();
        [$timer, $memory] = Kernel::initConstants();
        Kernel::loadFunctionsFile();
        Env::load();
        Config::load();
        $this->isDebug = Config::get('app.debug', false);

        if ($this->isDebug) {
            // Inicia la medición del tiempo
            $this->start_time = $timer;
            $this->memory = $memory;
            Debug::registerRoutes();
        } else {
            unset($timer);
        }

        if (!$consoleMode) {
            Request::make();
            Response::make();
        }

        // Llamar el 'register' de los servicios
        $this->providers = Kernel::registerServiceProviders($consoleMode);

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
            // Este try esta vinculado al manejo de los controladores
            try {
                if (Config::get('security.rate_limit.enable', true)) {
                    $rateLimit = new RateLimit(
                        Config::get('security.rate_limit.driver', 'session'),
                        Config::get('security.rate_limit.limit', 60),
                        Config::get('security.rate_limit.ban_time', 3600)
                    );

                    $rateLimit->roadmap();
                }

                $route = $app->coincidenceRoute();

                $through = new CarryThrough($route);

                // Try interno para manejos de excepciones HTTP
                try {
                    MiddlewareHandler::runMiddlewaresFromConfig('middleware.app');

                    if (empty($route)) {
                        /**
                         * Pagina no encontrada, el sitio al que esta intentando acceder,
                         * no esta definido en las rutas de la aplicación.
                         */
                        throw new \Exception('Not found', 404);
                    }

                    MiddlewareHandler::applyMiddleware($route['middleware'] ?? []);

                    // Ejecutar los middlewares según sea el caso
                    if (Request::make()->isAjax) {
                        MiddlewareHandler::runMiddlewaresFromConfig('middleware.api');
                    } else {
                        MiddlewareHandler::runMiddlewaresFromConfig('middleware.web');
                    }

                    // Ejecutar el renderizado de la aplicación
                    $render = $app->runRender($through->callIf());

                    Response::send();

                    echo $render;
                } catch (\Throwable $th) {
                    ExceptionHandler::isHttpExceptions($th, $through, $app);
                }
            } catch (\Throwable $th) {
                ExceptionHandler::runExceptionHandlerView($th, $app);
                return '';
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

        // Aplicar los mutadores
        $through->applyMutators();

        // Render JSON en caso de ser una respuesta para la API
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
        $uri = trim($request->uri, '/');

        // Obtener la ruta coincidente
        foreach (Router::$routes[$request->method] as $route) {
            $route['path'] = trim($route['url'], '/');

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
