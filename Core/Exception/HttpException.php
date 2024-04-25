<?php

namespace Core\Exception;

use Core\Foundation\Application;
use Core\Foundation\CarryThrough;
use Core\Foundation\Debugging;
use Core\Foundation\Render;
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
        429 => 'use-internal',
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
     * @param Throwable $errorCode
     * @param CarryThrough $through
     * @param Application $app
     * @return string
     */
    public function prepareRender(\Throwable $error, CarryThrough $through, Application $app): string
    {
        $configAction = Config::get("resources.http_exceptions.{$error->getCode()}");

        if ($configAction === null || $configAction === false) {
            $configAction = $this->listExceptionActions[$error->getCode()];
        }

        if ($configAction === 'use-internal') {
            return $through->renderByErrorCode($app, $error);
        }

        if (is_string($configAction) && !empty($configAction)) {
            $renderHtml = (new Render)->view($configAction);

            if ($app->isDebug) {
                Debugging::renderDebugBar($app, $renderHtml);
            }

            return $renderHtml;
        }

        $through = new CarryThrough([
            'callback' => $configAction,
        ]);


        return $app->runRender($through);
    }
}
