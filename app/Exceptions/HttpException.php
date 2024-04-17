<?php

namespace App\Exceptions;

use Core\Foundation\Request;

class HttpException
{
    /**
     * Función disparada cuando ocurre una excepción Http
     */
    public function handle(): void
    {
        # code...
    }

    /**
     * Listado de acciones a cada excepción Http
     **/
    public function getListExceptionsActions(Request $request): array
    {
        return [
            400 => false,
            404 => false,
            500 => false,
        ];
    }
}
