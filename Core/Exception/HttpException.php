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

    /**
     * Configura las acciones de las excepciones
     */
    public function configActions(int $code): bool
    {
        // Verifica primero si el código es de una acción válida
        if (array_key_exists($code, $this->listExceptionActions)) {
            return false;
        }

        return true;
    }
}
