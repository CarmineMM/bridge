<?php

return [
    /**
     * Path hacia las vistas para el renderizado
     */
    'view_path' => app_path('resources.views'),

    /**
     * Indica la ruta de los archivos para las excepciones de tipo HTTP,
     * la ruta debe ser directa a los archivos, por ejemplo: app_path('resources.views.errors.404'),
     * también se pueden especificar acciones como en las rutas.
     */
    'http_exceptions' => [
        400 => false,
        404 => function () {
            return 'hola';
        },
        429 => false,
    ],
];
