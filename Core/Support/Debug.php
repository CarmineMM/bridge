<?php

namespace Core\Support;

use Core\Foundation\Filesystem;
use Core\Foundation\Response;
use Core\Foundation\Router;

class Debug
{
    /**
     * Recursos para debug
     */
    const resources = [
        'js-deps' => '__bridge-deps-js',
        'css'     => '__bridge-debugbar-css',
        'js'      => '__bridge-debugbar-js',
    ];

    /**
     * Dump en pantalla
     *
     * @param [type] ...$vars
     * @return void
     */
    public static function dump(...$vars): void
    {
        $backtrace = debug_backtrace();
        $file = $backtrace[0]['file'];
        $line = $backtrace[0]['line'];

        foreach ($vars as $var) {
            if ($var instanceof Collection) {
                var_dump([
                    'Core\Support\Collection' => $var->toArray(),
                ]);
                continue;
            }
            var_dump($var);
        }
        echo '</pre>';
    }

    /**
     * Registrar rutas para debug
     */
    public static function registerRoutes(): void
    {
        Router::$routes['GET'][] = [
            'url' => self::resources['js-deps'],
            'callback' => function () {
                Response::make()->setHeader('Content-Type', 'text/javascript');
                return file_get_contents(Filesystem::rootPath(['Core', 'resources', 'js', 'deps.js']));
            },
            'name' => 'js-deps',
            'middleware' => [],
            'method' => 'GET'
        ];

        Router::$routes['GET'][] = [
            'url' => self::resources['css'],
            'callback' => function () {
                Response::make()->setHeader('Content-Type', 'text/css');
                return file_get_contents(Filesystem::rootPath(['Core', 'resources', 'css', 'debugbar.css']));
            },
            'name' => 'debugging.css',
            'middleware' => [],
            'method' => 'GET'
        ];

        Router::$routes['GET'][] = [
            'url' => self::resources['js'],
            'callback' => function () {
                Response::make()->setHeader('Content-Type', 'text/javascript');
                return file_get_contents(Filesystem::rootPath(['Core', 'resources', 'js', 'debugbar.js']));
            },
            'name' => 'debugging.js',
            'middleware' => [],
            'method' => 'GET'
        ];
    }
}
