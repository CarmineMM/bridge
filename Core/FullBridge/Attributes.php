<?php

namespace Core\FullBridge;

use DOMNode;

class Attributes
{
    /**
     * Obtiene las directivas a las cuales hay que prestarle atención para el SSR
     */
    public  static function directives(): array
    {
        return [
            'bridge:model',
            'bridge:for'
        ];
    }

    /**
     * Lista de tags las cuales se les debe colocar SSR
     */
    public  static function tags(): array
    {
        return [
            'template',
        ];
    }

    public  static function loopCycle(DOMNode $el): void
    {
        foreach ($el->childNodes as $node) {
            dump($node->nodeName);
        }
    }
}
