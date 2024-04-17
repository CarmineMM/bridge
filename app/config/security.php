<?php

return [
    /**
     * Nombre del token CSRF
     */
    'token_name' => 'CSRF_TOKEN',

    /**
     * Tiempo de expiración del token CSRF,
     * expresado en segundos.
     */
    'token_expire' => 3600,

    /**
     * Opciones del CORS
     */
    'cors' => [],

    /**
     * Las peticiones pueden ser limitadas
     * para evitar ataques de fuerza bruta.
     * EL rate Limit establece que tantas cantidad de peticiones por minutos son permitidas.
     */
    'rate_limit' => [
        /**
         * Enable rate limit
         */
        'enable' => true,

        /**
         * Cantidad de peticiones permitidas por minuto
         */
        'limit' => 60,
    ],
];
