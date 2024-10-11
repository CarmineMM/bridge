<?php

namespace Core\FullBridge;

use DOMDocument;
use Exception;

class LoadBridgeComponent
{
    public static function call($call): string
    {
        if (!is_subclass_of($call, Component::class)) {
            throw new Exception('Call must be an instance of Component');
        }

        $self = new Self;

        $component = new $call();

        Lifecycle::mount($component);

        return $self->__render($component);
    }

    /**
     * Public render
     */
    public function __render(Component $component): string
    {
        $doc = new DOMDocument('1.0', 'UTF-8');

        // Run pre render
        $render = $component->render();
        $id = random_int(1, 1000);
        $componentClass = $component::class;
        $render = preg_replace("/>/", " bridge:component='{$componentClass}' bridge:id='$id'>", $render, 1);

        // Load HTML
        $doc->loadHTML(
            mb_convert_encoding($render, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        return $doc->saveHTML();
    }
}
