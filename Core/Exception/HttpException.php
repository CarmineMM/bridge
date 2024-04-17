<?php

namespace Core\Exception;

use Core\Foundation\Application;
use Core\Foundation\CarryThrough;
use Core\Loaders\Config;

class HttpException
{
    /**
     * Lista de excepciones de la app,
     * configuradas por el usuario
     */
    private array $listExceptionActions = [
        400 => 'use-internal',
        404 => 'use-internal',
        500 => 'use-internal',
    ];

    /**
     * Configura las acciones de las excepciones
     */
    public function configActions(int $code): bool
    {
        // Verifica primero si el código es de una acción válida
        if (!array_key_exists($code, $this->listExceptionActions)) {
            return false;
        }

        return true;
    }

    /**
     * Al preparar para el renderizado este establece el response y el status code,
     * ademas de que se encarga de encontrar la vista
     *
     * @param integer $error
     * @param [type] $through
     * @return string
     */
    public function prepareRender(int $error, CarryThrough $through, Application $app): string
    {
        $configAction = Config::get("resources.http_exceptions.{$error}");

        if ($configAction === null || $configAction === false) {
            $configAction = $this->listExceptionActions[$error];
        }

        if ($configAction === 'use-internal') {
            return match ($error) {
                400 => 'Error 400',
                404 => $through->return404($app),
                500 => 'Error 500',
            };
        }

        return '';
    }
}
