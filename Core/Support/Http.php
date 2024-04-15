<?php

namespace Core\Support;

class Http
{
    /**
     * Verifica si la petición entrante fue de ajax
     */
    public static function isAjax(): bool
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')
            || (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json')
            || (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/x-www-form-urlencoded')
            || (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'multipart/form-data')
            | (isset($_SERVER['Accept']) && strpos($_SERVER['Accept'], 'application/json') !== false);
    }
}
