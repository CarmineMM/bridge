<?php

namespace Core\Exception;

class HttpException
{
    /**
     * Lista de excepciones de la app,
     * configuradas por el usuario
     */
    private array $listExceptionActions = [
        400 => false,
        404 => false,
        500 => false,
    ];
}
