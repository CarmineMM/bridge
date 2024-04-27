<?php

namespace Core\Foundation;

use Core\Loaders\Config;
use Exception;

class Router extends Middlewares
{
    /**
     * Instance
     */
    public static array $routes = [
        'GET'  => [],
        'POST' => [],
    ];

    /**
     * Ultima ruta agregada
     */
    public static $lastRoute = [];

    /**
     * Ruta Get
     * 
     * @param string $url URL de la ruta
     * @param callable|array $callback Función o método (controlador) a ejecutar
     * @param string $name Nombre de la ruta
     * @param array $middleware Middlewares aplicados sobre de la ruta
     * @return Router
     */
    public static function get(
        string $url,
        callable|array $callback,
        string $name = '',
        array $middleware = []
    ): Router {
        self::$lastRoute = [
            'url'        => trim($url, '/'),
            'callback'   => $callback,
            'name'       => $name,
            'middleware' => $middleware,
            'method'     => 'GET',
            'rate_limit' => false,
            'ban_time'   => false,
        ];

        self::$routes['GET'][] = self::$lastRoute;

        return new self;
    }

    /**
     * Ruta Get
     */
    public static function post(
        string $url,
        callable|array $callback,
        string $name = '',
        array $middleware = []
    ): Router {
        self::$lastRoute = [
            'url'        => trim($url, '/'),
            'callback'   => $callback,
            'name'       => $name,
            'middleware' => $middleware,
            'method'     => 'POST',
            'rate_limit' => false,
            'ban_time'   => false,
        ];

        self::$routes['POST'][] = self::$lastRoute;

        return new self;
    }

    /**
     * Establece el nombre de la ruta
     *
     * @param string $name
     * @return Router
     */
    public function name(string $name): Router
    {
        return $this->__setProp('name', $name);
    }

    /**
     * Establece un rate limit para la ruta
     * 
     * @param int $limit Cantidad de peticiones por minuto
     */
    public function rateLimit(int $limit): Router
    {
        return $this->__setProp('rate_limit', $limit);
    }

    /**
     * Establece el tiempo de ban para la ruta,
     * para que el ban time de resultado, a la ruta se le debe establecer un rate limit.
     * 
     * @param int $time El tiempo de ban en segundos
     */
    public function banTime(int $time): Router
    {
        return $this->__setProp('ban_time', $time);
    }

    /**
     * Establece los middlewares de la ruta
     *
     * @param string $middleware
     * @return Router
     */
    public function middleware(array $middlewares): Router
    {
        return $this->__setProp('middleware', $middlewares);
    }

    /**
     * Establece una prop dentro de la ruta actual agregado
     *
     * @param string $prop
     * @param mixed $value
     * @return Router
     */
    private function __setProp(string $prop, mixed $value): Router
    {
        self::$routes[self::$lastRoute['method']] = array_map(function ($route) use ($prop, $value) {
            if ($route['url'] === self::$lastRoute['url']) {
                $route[$prop] = $value;
            }

            return $route;
        }, self::$routes[self::$lastRoute['method']]);

        return $this;
    }

    /**
     * Obtiene la ruta por su nombre
     */
    public static function getRouteByName(string $name): array
    {
        $find = [];

        foreach ([...Router::$routes['POST'], ...Router::$routes['GET']] as $route) {
            if ($route['name'] === $name) {
                $find = $route;
                break;
            }
        }

        return $find;
    }

    /**
     * Lleva la url construida
     */
    public static function routeTo(string $name): string
    {
        $find = Router::getRouteByName($name);

        if (empty($find)) {
            throw new Exception("Bridge: named path '{$name}', not been found", 500);
        }

        return AppConfig::url($find['url']);
    }
}
