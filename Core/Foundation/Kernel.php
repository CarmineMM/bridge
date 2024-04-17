<?php

namespace Core\Foundation;

use Dotenv\Dotenv;

class Kernel
{
    /**
     * Inicializar constantes necesarias
     * 
     * @lifecycle 1: Set Kernel Constants
     */
    public static function initConstants(): array
    {
        if (defined('ROOT_PATH')) {
            throw new \Exception('The Constants have be declared');
        }
        $timer = microtime(true);
        $memory = memory_get_usage();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $array = explode(DIRECTORY_SEPARATOR, PUBLIC_PATH);
        array_pop($array);

        // Define la ruta de carpetas de la raíz
        define('ROOT_PATH', implode(DIRECTORY_SEPARATOR, $array) . DIRECTORY_SEPARATOR);
        unset($array);

        return [$timer, $memory];
    }

    /**
     * Carga archivos de funciones rápidas
     * 
     * @lifecycle 2: Load Functions File
     */
    public static function loadFunctionsFile(): void
    {
        if (file_exists(ROOT_PATH . 'Core/functions.php')) {
            require_once ROOT_PATH . 'Core/functions.php';
        }
    }
}
