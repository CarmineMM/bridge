<?php

/**
 * Registra las rutas y middleware de la aplicación
 * 
 * @author Carmine Maggio <carminemaggiom@gmail.com>
 * @package Bridge Framework
 */
return [
    /**
     * Listado de rutas web
     */
    'web' => [
        [
            'files'  => [app_path('routes/web.php')],
            'prefix' => '',
            'middleware' => [],
            'name' => '',
        ],
    ],
    /**
     * Listado para la API
     */
    'api' => [
        [
            'files' => [],
            'prefix' => 'api',
            'middleware' => [],
            'name' => 'api.',
        ]
    ]
];
