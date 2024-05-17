<?php

namespace Core\Exception;

use Core\Foundation\Application;
use Core\Foundation\CarryThrough;
use Core\Foundation\Response;
use Core\Loaders\Config;

class ExceptionHandler
{
    /**
     * Instance
     *
     * @var [type]
     */
    private array $exceptionList = [];

    /**
     * Instancia del contenedor
     *
     * @var Container|null
     */
    private static ?ExceptionHandler $instance = null;

    /**
     * Establece la instancia
     */
    public static function setInstance(): ?ExceptionHandler
    {
        if (is_null(self::$instance)) {
            $self = new self();
            self::$instance = $self;
        }

        return self::$instance;
    }

    /**
     * La idea es almacenar las excepciones que van ocurren en la app
     *
     * @param \Throwable $error
     * @return void
     */
    public static function addExceptionList(\Throwable $error): void
    {
        self::setInstance()->exceptionList[] = [
            'severity' => 'error',
            'code' => $error->getCode(),
            'message' => $error->getMessage(),
            'file' => $error->getFile(),
            'line' => $error->getLine(),
            'trace' => $error->getTrace(),
            'previous' => $error->getPrevious(),
            'previousTrace' => $error->getPrevious() ? $error->getPrevious()->getTrace() : '',
        ];
    }

    /**
     * Agrega warning a la lista
     */
    public static function addWarningList(array $error): void
    {
        self::setInstance()->exceptionList[] = [
            'severity' => 'warning',
            'code' => $error[0],
            'message' => $error[1],
            'file' => $error[2],
            'line' => $error[3],
            'trace' => [],
            'previous' => null,
            'previousTrace' => ''
        ];
    }

    /**
     * Obtiene el listado de excepciones que ha ocurrido en la aplicación	
     */
    public static function getList(): array
    {
        return static::setInstance()->exceptionList;
    }

    /**
     * Verifica si es un error del tipo HTTP y rendering la vista subsecuente, 
     * si no es un error HTTP, se lanza una excepción
     */
    public static function isHttpExceptions(\Throwable $error, CarryThrough $through, Application $app): void
    {
        $httpException = new HttpException();
        if (!$httpException->configActions($error->getCode())) {
            if ($app->isDebug) {
                throw new \Exception($error->getMessage(), $error->getCode(), $error);
            }

            // Render de un error 500
        }

        if ($app->isDebug) {
            static::addExceptionList($error);
        }

        echo $httpException->prepareRender($error, $through, $app);
    }

    /**
     * Guarda los warnings que ocurren en la aplicación
     */
    public static function saveWarnings(): void
    {
        set_error_handler(function (...$params) {
            ExceptionHandler::addWarningList($params);
        });
    }

    /**
     * Ejecuta un exception handle view para ver lo errores de una mejor forma.
     * O ejecuta un error 500 en caso de tener el debug apagado
     */
    public static function runExceptionHandlerView(\Throwable $error, Application $app): void
    {
        $through = new CarryThrough();

        if (!Config::get('app.debug', false)) {
            echo $through->renderByErrorCode($app, $error);
            return;
        }

        $code = $error->getCode();

        if ($code <= 500 && $code >= 400) {
            ExceptionHandler::isHttpExceptions($error, $through, $app);
            return;
        }

        static::addExceptionList($error);

        Response::make()->setStatusCode($code > 550 || $code < 300 ? 500 : $code);

        Response::send();

        echo $through->renderExceptionHandlerr($app, $error);
    }
}
