<?php

namespace Core\Exception;

use Core\Foundation\CarryThrough;

class ExceptionHandle
{
    /**
     * Verifica si es un error del tipo HTTP y rendering la vista subsecuente, 
     * si no es un error HTTP, se lanza una excepción
     *
     * @param \Throwable $error
     * @param CarryThrough $through
     * @return void
     */
    public static function isHttpExceptions(\Throwable $error, CarryThrough $through): void
    {
        echo 'entre caa';
    }
}
