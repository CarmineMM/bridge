<?php

namespace Core\Foundation;

use Core\Loaders\Config;
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
        $timer = microtime(true);
        $memory = memory_get_usage();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!defined('ROOT_PATH')) {
            $array = explode(DIRECTORY_SEPARATOR, PUBLIC_PATH);
            array_pop($array);

            // Define la ruta de carpetas de la raíz
            define('ROOT_PATH', implode(DIRECTORY_SEPARATOR, $array) . DIRECTORY_SEPARATOR);
            unset($array);
        }

        return [$timer, $memory];
    }

    /**
     * Carga archivos de funciones rápidas
     * 
     * @lifecycle 2: Load Functions File
     */
    public static function loadFunctionsFile(): void
    {
        $loadFile = Filesystem::rootPath(['Core', 'functions.php']);

        if (file_exists($loadFile)) {
            require_once $loadFile;
        } else {
            echo "No se cargaron los archivos de funciones: {$loadFile}";
        }
    }

    /**
     * Registra los service providers de la aplicación
     *
     * @return array Devuelve la instancia de todos los service providers
     */
    public static function registerServiceProviders(bool $consoleMode): array
    {
        $providers = Config::get('app.providers');

        foreach ($providers as $key => $value) {
            $providers[$key] = new $value();
            if (method_exists($providers[$key], 'register')) {
                $providers[$key]->register($consoleMode);
            }
        }

        return $providers;
    }

    /**
     * Boot de los service provider
     */
    public static function runBootServiceProvider(array $providers): void
    {
        foreach ($providers as $provider) {
            if (method_exists($provider, 'boot')) {
                $provider->boot();
            }

            Container::make([
                'observers' => $provider->getObservers(),
            ]);
        }
    }
}
