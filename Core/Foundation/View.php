<?php

namespace Core\Foundation;

use Core\Loaders\Config;

class View
{
    /**
     * Configuración para la view path
     *
     * @var string
     */
    public string $config_view_path = 'resources.view_path';

    /**
     * Obtiene el path directo hacia la vista
     */
    protected function getFilePath(string $view): string
    {
        $viewRender = Filesystem::constructPath([
            Config::get($this->config_view_path),
            ...Filesystem::explodeStringPath($view)
        ]);

        // Agregar el .php en caso de no tenerlo
        if (substr($viewRender, -4) !== '.php') {
            $viewRender .= '.php';
        }

        if (!file_exists($viewRender)) {
            throw new \Exception("The file '$view ($viewRender)' not exists", 500);
        }

        return $viewRender;
    }
}
