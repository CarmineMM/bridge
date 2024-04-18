<?php

namespace Core\Exception;

use Core\Foundation\Application;
use Core\Foundation\CarryThrough;
use Core\Loaders\Config;

class ExceptionHandle
{
    /**
     * Instance
     *
     * @var [type]
     */
    private static array $exceptionList = [];

    /**
     * La idea es almacenar las excepciones que van ocurren en la app
     *
     * @param \Throwable $error
     * @return void
     */
    public static function addExceptionList(\Throwable $error): void
    {
        self::$exceptionList[] = $error;
    }

    /**
     * Obtiene el listado de excepciones que ha ocurrido en la aplicación	
     */
    public static function getList(): array
    {
        return self::$exceptionList;
    }

    /**
     * Verifica si es un error del tipo HTTP y rendering la vista subsecuente, 
     * si no es un error HTTP, se lanza una excepción
     */
    public static function isHttpExceptions(\Throwable $error, CarryThrough $through, Application $app): void
    {
        $httpException = new HttpException();
        if (!$httpException->configActions($error->getCode())) {
            if (Config::get('app.debug', true)) {
                throw new \Exception("Is Not error HTTP", $error->getCode());
            }

            // Render de un error 500
        }

        static::addExceptionList($error);

        echo $httpException->prepareRender($error, $through, $app);
    }

    public static function saveWarnings(): void
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            dump('errores');
        });
    }
}
