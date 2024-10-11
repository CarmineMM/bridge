<?php

namespace Core\FullBridge;

trait RenderBridgeComponent
{
    /**
     * Este es como se puede usar el componente dentro del front, 
     * ej: ComponentName = 'componentName' se usaría en el front como: <componentName />
     */
    protected string $componentName = '';

    /**
     * Component name to render
     */
    public function getName(): string
    {
        return $this->componentName;
    }

    /**
     * Render view
     */
    public function view(string $viewPath)
    {
        # code...
    }
}
