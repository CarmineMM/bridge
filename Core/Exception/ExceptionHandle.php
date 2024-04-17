<?php

namespace Core\Exception;

use Core\Foundation\Application;
use Core\Foundation\CarryThrough;
use Core\Loaders\Config;

class ExceptionHandle
{
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

        echo $httpException->prepareRender($error->getCode(), $through, $app);
    }
}
