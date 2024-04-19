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
     * Data intercambiable en los layouts y las vistas.
     * Pero no en los includes
     */
    private array $data = [];

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
        $this->data = $data;

        ob_start();
        extract($data);

        include $viewRender;
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Incluye un archivo de vista
     *
     * @param string $view La estructura de los includes sigue la nomenclatura de las vistas
     * @param array $data
     * @return string|false
     */
    public function include(string $view, array $data = []): string|false
    {
        $viewRender = $this->getFilePath($view);

        extract($data);
        include $viewRender;

        return '';
    }

    /**
     * Extiende de un layout
     */
    public function extend(string $layout): void
    {
        $this->layout = $this->getFilePath($layout);
    }

    /**
     * Dentro de un layout, se establece en que lugar estará el section,
     * pueden haber varios sections en un layout. 
     */
    public function section(string $name)
    {
        foreach ($this->sections as $key => $content) {
            if ($name === $key) echo $content;
        }
    }

    /**
     * Cuando un layout esta cargado este método se encarga de indicar,
     * desde donde se encuentra el contenido que este registrado en un section
     *
     * @param string $section Section a la que se le asigna el contenido (Dentro del layout)
     */
    public function start(string $section)
    {
        $this->current_section = $section;
        ob_start();
    }

    /**
     * Le da fin a un section, esta debe estar dentro de un layout,
     * y ser marcada como "section" y haberse iniciado con "start"
     */
    public function end(): void
    {
        $content = ob_get_clean();
        $this->sections[$this->current_section] = $content;
        $this->current_section = null;

        // Incluir la sección
        if (count($this->sections) && $this->layout && is_file($this->layout)) {
            extract($this->data);
            include_once $this->layout;
        }
    }
}
