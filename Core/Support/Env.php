<?php

namespace Core\Support;

use Core\Exception\ExceptionHandler;
use Dotenv\Dotenv;

class Env
{
    /**
     * Init dot env initialization
     *
     * @lifecycle 3: Load DotEnv
     */
    public static function load(): void
    {
        try {
            $dotenv = Dotenv::createImmutable(ROOT_PATH);
            $dotenv->load();
        } catch (\Throwable $th) {
            ExceptionHandler::addExceptionList($th);
        }
    }

    /**
     * Obtiene la variable de entorno
     */
    public static function get(string $get, string $default = ''): string
    {
        return $_ENV[$get] ?? $default;
    }
}
