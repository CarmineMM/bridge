<?php

namespace Core\FullBridge;

/**
 * @package Full Bridge
 * @author Carmine Maggio <carminemaggiom@gmail.com>
 * @version 1.0.0
 */
class Component
{
    use RenderBridgeComponent;

    /**
     * Template a buscar para el componente
     */
    protected string $template = '';

    /**
     * Lista de errores
     */
    private array $errors = [];

    /**
     * Establece el template de forma automática
     */
    protected function setTemplate(): string
    {
        return '';
    }

    /**
     * Render, el render es un string 
     */
    public function render(): string
    {
        return '';
    }
}
