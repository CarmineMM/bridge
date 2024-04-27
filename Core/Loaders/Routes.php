<?php

namespace Core\Loaders;

use Core\Foundation\Router;

class Routes
{
    /**
     * Carga las rutas web
     * 
     * @lifecycle 7.1: Load Web Routes
     * @lifecycle 7.2: Load Api Routes
     */
    public static function loadForm($state = 'web'): void
    {
        foreach (Config::get("routes.{$state}") as $value) {
            foreach ($value['files'] as $file) {
                require_once $file;
            }
            foreach (Router::$routes as $key => $route) {
                Router::$routes[$key] = array_map(function ($route) use ($value) {
                    if ($route['hasLoad'] ?? false) {
                        return $route;
                    }

                    $route['name'] = ($value['name'] ?? '') . ($route['name'] ?? '');
                    $route['middleware'] = array_merge(
                        $value['middleware'] ?? [],
                        $route['middleware'] ?? []
                    );

                    $route['url'] = $value['prefix'] . $route['url'];
                    $route['hasLoad'] = true;

                    return $route;
                }, Router::$routes[$key]);
            }
        }
    }

    /**
     * Carga rutas para el debug
     *
     * @return void
     */
    public function loadDebugRoutes(): void
    {
    }
}
