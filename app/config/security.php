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
     * Método de ssl de encriptación de datos
     */
    'encrypt_data' => 'AES-128-ECB',

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
        'enable' => false,

        /**
         * Cantidad de peticiones permitidas por minuto
         */
        'limit' => 60,

        /**
         * Los driver de manejo de rate limit
         * disponibles son: 
         * 
         * - session: Manejo por variable global Session
         */
        'driver' => 'session',

        /**
         * Tiempo de ban expresado en segundos.
         */
        'ban_time' => 360
    ],
];
