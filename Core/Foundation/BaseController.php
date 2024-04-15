<?php

namespace Core\Foundation;

/**
 * Controlador base para ayudas a los controladores 
 * 
 * @author Carmine Maggio <carminemaggiom@gmail.com>
 * @package Bridge Framework
 * @version 1.0.0
 */
class BaseController implements \Core\Implements\ShouldController
{
    /**
     * Rendering view
     * 
     * @param string $view La vista debe estar ubicada en la carpeta indicada por el config
     * @param array $data Datos que se pasan a la vista
     * @return string|false
     * @version 1.0.0
     */
    protected function view(string $view, array $data = []): string|false
    {
        return (new Render)->view($view, $data);
    }

    /**
     * Método implementado para instancia ciertas configuraciones dentro de los controladores
     */
    public function handleControllerImplements(): void
    {
        //...
    }
}
