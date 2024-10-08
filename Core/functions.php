<?php

use Core\Exception\HttpException;
use Core\Foundation\Context;
use Core\Foundation\Filesystem;
use Core\Foundation\Router;
use Core\Support\Collection;
use Core\Support\Debug;

if (!function_exists('__')) {
    /**
     * Traduce un texto
     *
     * @param string $key Key del texto
     * @param array $args Variables a reemplazar
     * @param string $lang Idioma (Asignado manualmente)
     * @return string Si no encuentra la traducción devuelve el key
     */
    function __(string $key, array $args = [], string $lang = ''): string
    {
        return Core\Translate\Lang::get($key, $args, $lang);
    }
}

if (!function_exists('app_path')) {
    /**
     * Devuelve la ruta de la carpeta 'app'
     *
     * @param array $construct
     * @return string
     */
    function app_path(array|string $construct = []): string
    {
        return Core\Foundation\Filesystem::appPath(
            is_string($construct) ? Filesystem::explodeStringPath(trim($construct, '/')) : $construct
        );
    }
}

if (!function_exists('root_path')) {
    /**
     * devuelve o construye el path desde la ruta raíz del framework
     *
     * @param array $construct
     * @return string
     */
    function root_path(array|string $construct = []): string
    {
        return Core\Foundation\Filesystem::rootPath(
            is_string($construct) ? Filesystem::explodeStringPath(trim($construct, '/')) : $construct
        );
    }
}

if (!function_exists('dump')) {
    /**
     * Dump en pantalla
     *
     * @param [type] ...$vars
     * @return void
     */
    function dump(...$vars): void
    {
        Debug::dump(...$vars);
    }
}

if (!function_exists('config')) {
    /**
     * Obtiene un valor de la configuración
     */
    function config(string $key, mixed $default = null): mixed
    {
        return Core\Loaders\Config::get($key, $default);
    }
}

if (!function_exists('collection')) {
    /**
     * Objectos collections
     */
    function collection(array|object $data): Collection
    {
        return new Collection($data);
    }
}

if (!function_exists('route')) {
    /**
     * Obtiene la url hacia una ruta nombrada
     *
     * @param string $name Nombre de la ruta
     * @return string Si la ruta no existe, devuelve una excepción
     */
    function route(string $name): string
    {
        return Router::routeTo($name);
    }
}

if (!function_exists('context')) {
    /**
     * Store and state
     *
     * @return Context
     */
    function context(): Context
    {
        return new Core\Foundation\Context();
    }
}

if (!function_exists('abort')) {
    /**
     * Exceptions abort
     */
    function abort(string $message, int $code = 400): void
    {
        (new HttpException())->abort($message, $code);
    }
}
