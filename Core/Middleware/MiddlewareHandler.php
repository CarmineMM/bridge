<?php

namespace Core\Middleware;

use Core\Foundation\Request;

class MiddlewareHandler
{
    /**
     * Aplica los middlewares pasados por parámetros
     * 
     * @param array $middlewares
     */
    public static function applyMiddleware(array $middlewares): void
    {
        $request = Request::make();

        foreach ($middlewares as $middleware) {
            $instance = new $middleware();

            if (method_exists($instance, 'handle')) {
                $instance->handle($request);
            }
        }
    }
}
