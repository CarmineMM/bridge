<?php

namespace Core\FullBridge;

use Attribute;
use Core\Support\Collection;
use Core\Support\Str;
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
        $publicProperties = new Collection(get_object_vars($component));
        $componentClass = (new Str($component::class))->replace('\\', '.')->getString();
        $render = preg_replace("/>/", " bridge:data='FullBridgeComponent({ data: {$publicProperties->toJson()}, componentName: \"$componentClass\" })'>", $render, 1);

        // Load HTML
        $doc->loadHTML(
            mb_convert_encoding($render, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        // Se recorren todos los tags posibles del HTML
        // foreach (Attributes::ModelTags as $tag) {
        //     $nodes = $doc->getElementsByTagName($tag)->getIterator();

        //     foreach ($nodes as $node) {
        //         // Ahora se recorren los atributos dentro del node (tag) de HTML
        //         foreach (Attributes::ModelProperties as $property) {
        //             dump($node->nodeName);
        //             $model = $node->getAttribute($property);
        //             $value = $publicProperties->get($model);

        //             // Hacer el reemplazo de valores dentro de las etiquetas HTML según sea el caso
        //             if ($model && $value) {
        //                 // Valor de 'Values'
        //                 if (in_array($tag, Attributes::AddValuesToNode)) {
        //                     $node->setAttribute('value', $value);
        //                 } else if (is_string($value)) {
        //                     $node->nodeValue = $value;
        //                 } else if (is_array($value)) {
        //                     dump('array');
        //                 }
        //             }
        //         }
        //     }
        // }

        foreach (Attributes::tags() as $tag) {
            $elements = $doc->getElementsByTagName($tag);

            // Realizar el reemplazo por TagsName
            foreach ($elements as $el) {

                // Iterar las directivas sobre el elemento
                foreach (Attributes::directives() as $directive) {
                    $attributeValue = $el->getAttribute($directive);

                    if (!$attributeValue) {
                        continue;
                    }

                    if (str_contains($directive, ':for')) {
                        // Attributes::loopCycle($el);
                    }
                }
            }
        }

        return $doc->saveHTML();
    }
}
