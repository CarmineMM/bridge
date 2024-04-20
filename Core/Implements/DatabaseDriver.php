<?php

namespace Core\Implements;

interface DatabaseDriver
{
    /**
     * Obtener todos los registros de la tabla
     */
    public function all(array $columns = ['*']): array;

    /**
     * Busca un registro especifico, devuelve solo 1 elemento
     */
    public function find(string|int $find): array;
}
