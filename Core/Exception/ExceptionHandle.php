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
    private array $exceptionList = [];

    /**
     * Instancia del contenedor
     *
     * @var Container|null
     */
    private static ?ExceptionHandle $instance = null;

    /**
     * Establece la instancia
     */
    public static function setInstance(): ?ExceptionHandle
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
            if (Config::get('app.debug', true)) {
                throw new \Exception("Is Not error HTTP", $error->getCode());
            }

            // Render de un error 500
        }

        if (Config::get('app.debug', true)) {
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
            dump('probar');
            ExceptionHandle::addWarningList($params);
        });
    }
}
