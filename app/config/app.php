<?php

use Core\Support\Env;

/**
 * Application configuration
 * 
 * @author Carmine Maggio <carminemaggiom@gmail.com>
 * @package Bridge Framework
 */
return [
    /**
     * Nombre de la aplicación.
     */
    'name' => Env::get('APP_NAME', 'Bridge Framework'),

    /**
     * Debug mode.
     * 
     * El modo debug permite ver los errores de la aplicación, optimización y ciertos logs
     */
    'debug' => Env::get('APP_DEBUG', true),

    /**
     * Entorno actual del framework.
     */
    'env' => Env::get('APP_ENV', 'local'),

    /**
     * URL de la aplicación.
     */
    'url' => Env::get('APP_URL', 'http://localhost:8080'),

    /**
     * Idioma de la aplicación.
     */
    'locale' => Env::get('APP_LOCALE', 'en'),

    /**
     * Idioma de fallback.
     * 
     * En caso de no existir un archivo o configuración de idioma al de la aplicación, carga el fallback.
     */
    'fallback_locale' => Env::get('APP_FALLBACK_LOCALE', 'en'),

    /**
     * Archivos de idioma.
     */
    'locale_folder' => 'app/locale',
];
