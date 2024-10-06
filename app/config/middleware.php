<?php

return [
    /**
     * Middlewares globales de la aplicación
     */
    'app' => [],

    /**
     * Middlewares ejecutados en el request de la web
     */
    'web' => [
        \App\Middlewares\ValidateCsrfToken::class,
    ],

    /**
     * Middlewares ejecutados en el request de la API
     */
    'api' => [],

    /**
     * Alias de middlewares
     */
    'named' => [],
];
