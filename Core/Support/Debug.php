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
        'css' => '__bridge-debugbar-css',
        'js'  => '__bridge-debugbar-js',
        'alpine' => 'alpine-3-13-8-js',
    ];

    /**
     * Dump en pantalla
     *
     * @param [type] ...$vars
     * @return void
     */
    public static function dump(...$vars): void
    {
        echo '<pre>';
        foreach ($vars as $var) {
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
            'url' => self::resources['alpine'],
            'callback' => function () {
                Response::make()->setHeader('Content-Type', 'text/javascript');
                return file_get_contents(Filesystem::rootPath(['Core', 'resources', 'js', 'alpine-3.13.8.min.js']));
            },
            'name' => 'alpine.js',
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
