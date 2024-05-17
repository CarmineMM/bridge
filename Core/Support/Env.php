<?php

namespace Core\Support;

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
        $dotenv = Dotenv::createImmutable(ROOT_PATH);
        $dotenv->load();
    }

    /**
     * Obtiene la variable de entorno
     */
    public static function get(string $get, string $default = ''): string
    {
        return $_ENV[$get] ?? $default;
    }
}
