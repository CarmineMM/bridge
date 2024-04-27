<?php

namespace Core\Foundation;

use Core\Loaders\Config;

/**
 * Esta clase contiene las configuraciones de la aplicación,
 * como el lenguaje, zona horaria, etc.
 * No confundir con la clase Config que carga los archivos de configuración.
 * 
 * @author Carmine Maggio <carminemaggiom@gmaik.com>
 * @package Bridge
 * @version 1.0.0
 */
class AppConfig
{
    /**
     * Devuelve el url de la aplicación, o construida por una ruta pasada.
     */
    public  static function url(string $route = ''): string
    {
        return Config::get('app.url') . '/' . trim($route, '/');
    }
}
