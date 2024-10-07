<?php

namespace Core\Support;

trait RequestHelpers
{
    /**
     * Método del request
     */
    public string $method = 'GET';

    /**
     * URL actual
     */
    public string $uri = '';

    /**
     * Indica si es el método de la petición
     */
    public function isMethod(string $method): bool
    {
        return $this->method === strtoupper($method);
    }

    /**
     * Comprueba si la uri actual concuerda con el parámetro
     *
     * @param string $string URI a comparar
     * @return boolean
     */
    public function isUri(string $string): bool
    {
        return trim($this->uri, '/') === trim($string, '/');
    }
}
