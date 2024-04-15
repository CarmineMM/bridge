<?php

namespace Core\Foundation;

use Core\Loaders\Config;

/**
 * Rendering o devuelve una vista, o objetos JSON
 */
class Render extends View
{
    /**
     * Layout de la vista
     */
    private ?string $layout = null;

    /**
     * Las secciones son partes de la vista que se pueden reemplazar,
     * estas están en los layouts y son utilizadas para que la vista principal sea vista en la sección indicada.
     */
    private array $sections = [];

    /**
     * La $current_section indica cual de todas las secciones en ejecución es la actual,
     * de esta forma se mapea el $content internamente en la sección actual.
     */
    private mixed $current_section = null;

    /**
     * Contenido de la vista, esta es impresa en el layout, 
     * siempre y cuando haya un section asociada
     */
    private mixed $content = null;

    /**
     * Rendering una vista
     *
     * @param string $view La vista debe estar ubicada en la carpeta indicada por el config
     * @param array $data Datos que se pasan a la vista
     * @return string|false
     */
    public function view(string $view, array $data = []): string|false
    {
        $viewRender = $this->getFilePath($view);

        ob_start();
        extract($data);

        include $viewRender;
        $content = ob_get_clean();

        return $content;
    }
}
