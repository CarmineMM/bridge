<?php

namespace Core\Support;

class Str
{
    /**
     * String original sin alterar
     */
    private string $original;

    /**
     * String
     */
    public function __construct(
        public string $string
    ) {
        $this->original = $this->string;
    }

    /**
     * Algunos Json no son imprimirles en HTML, por las comillas,
     * por ello se reemplazaran las comillas dobles y simples son reemplazado por su version entidad de HTML
     */
    public function toJsonHtml(): string
    {
        return htmlspecialchars(
            $this->string,
            ENT_QUOTES,
            'UTF-8'
        );
    }
}
