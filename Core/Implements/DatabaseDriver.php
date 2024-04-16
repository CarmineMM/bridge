<?php

namespace Core\Implements;

interface DatabaseDriver
{
    /**
     * Obtener todos los registros de la tabla
     */
    public function all(array $columns = ['*']): array;
}
