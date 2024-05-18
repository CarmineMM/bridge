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
     * Obtiene el path directo hacia la vista o archivo en las vistas
     */
    protected function getFilePath(string $view): string
    {
        $viewRender = Config::get($this->config_view_path) . Filesystem::constructPath(Filesystem::explodeStringPath($view));

        // Agregar el .php en caso de no tenerlo
        if (substr($viewRender, -4) !== '.php') {
            $viewRender .= '.php';
        }

        if (!file_exists($viewRender)) {
            throw new \Exception("Bridge Render Error: The file '$viewRender' not exists", 500);
        }

        return $viewRender;
    }
}
