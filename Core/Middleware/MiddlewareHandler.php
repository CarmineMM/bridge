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

        $middlewareStack = static::createStack($middlewares);

        $middlewareStack($request);
    }

    /**
     * Stacks the middlewares
     *
     * @param array $middlewares
     * @return void
     */
    protected static function createStack(array $middlewares)
    {
        $next = function ($request) {
            //...
        };

        while ($middleware = array_pop($middlewares)) {
            $next = function ($request) use ($middleware, $next) {
                $instance = new $middleware();

                if (method_exists($instance, 'handle')) {
                    return $instance->handle($request, $next);
                }

                return $next($request);
            };
        }

        return $next;
    }
}
