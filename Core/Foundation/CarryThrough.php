<?php

namespace Core\Foundation;

use Core\Loaders\Config;
use Core\Support\Collection;
use Exception;

/**
 * Realiza el deploy de la aplicación
 */
class CarryThrough
{
    /**
     * Item para el renderizado
     */
    public mixed $toRender = null;

    /**
     * Preparar para el despliegue de la app
     *
     * @lifecycle 19: Carry Through
     * @param Application $app
     * @param array $route
     * @return mixed
     */
    public function __construct(
        /**
         * Ruta actual
         *
         * @var array
         */
        public array $route = []
    ) {
        if (!empty($this->route)) {
            $this->toRender = $this->call($route['callback']);
        }
    }

    /**
     * Invoca una función o método a partir de un arreglo
     * 
     * @lifecycle 11: Call Action
     */
    public function call(callable|array $call): mixed
    {
        $request = Request::make();

        if (is_callable($call)) {
            return $call($request, ...$request->route->get('dynamic_params'));
        }

        if (is_array($call) && count($call) <= 2) {
            $namespaceClass = $call[0];

            if (!class_exists($namespaceClass)) {
                throw new \Exception("Class $namespaceClass not found", 500);
            }

            $instance = new $namespaceClass();

            // Inyectar la instancia de la respuesta
            if (method_exists($instance, 'handleControllerImplements')) {
                $instance->handleControllerImplements();
            }

            // Si el controlador tiene el método __invoke
            // Usar este para la invocación automática del controlador
            if (count($call) === 1) {
                if (!method_exists($instance, '__invoke')) {
                    throw new \Exception("Method __invoke not found in $namespaceClass", 500);
                }

                return $instance->__invoke(...$request->route->get('dynamic_params'));
            }

            // Si tiene dos elementos, el segundo es el método a invocar
            // El controlador debe contener este método para llevar a cabo la invocación
            if (count($call) === 2) {
                $method = $call[1];

                if (!method_exists($instance, $method)) {
                    throw new \Exception("Method $method not found in $namespaceClass", 500);
                }

                return $instance->$method(...$request->route->get('dynamic_params'));
            }
        }

        return '';
    }

    /**
     * Devuelve un 404
     *
     * @return string
     */
    public function renderByErrorCode(Application $app, \Throwable $error): string
    {
        $code = $error->getCode() > 550 ? 500 : $error->getCode();

        Response::make()->setStatusCode($code);

        if (Request::$instance->isAjax) {
            Response::make()->setHeader('Content-Type', 'application/json');

            return [
                'code'    => $code,
                'message' => $error->getMessage(),
            ];
        }

        $render = new Render;

        $render->config_view_path = 'framework.view_path';

        $renderHtml = $render->view("errors.{$code}", ['app' => $app]);

        if (Config::get('app.debug', false)) {
            Debugging::renderDebugBar($app, $renderHtml);
        }

        return $renderHtml;
    }

    /**
     * Hace el render el exception handle
     *
     * @param Application $app
     * @param \Throwable $error
     * @return string
     */
    public function renderExceptionHandler(Application $app, \Throwable $error): string
    {

        $render = new Render;

        $render->config_view_path = 'framework.view_path';

        $renderHtml = $render->view('exception-handle', [
            'error'   => $error,
            'app'     => $app,
            'request' => Request::make(),
        ]);

        if (Config::get('app.debug', false)) {
            Debugging::renderDebugBar($app, $renderHtml);
        }

        return $renderHtml;
    }

    /**
     * Rendering una vista
     */
    public function renderJson(): string
    {
        return $this->toRender instanceof Collection
            ? json_encode($this->toRender->toArray())
            : json_encode($this->toRender);
    }

    /**
     * Rendering un string
     */
    public function renderString(): string
    {
        return $this->toRender;
    }
}
