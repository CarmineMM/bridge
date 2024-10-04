<?php

namespace Core\Support;

use Normalizer;

class Str
{
    /**
     * String original sin alterar
     */
    private string $original;

    /**
     * Palabras para pluralizar,
     * siendo la primera palabra en singular,
     * la segunda en plural.
     */
    private array $pluralWords = [
        // English
        ['child', 'children'],
        ['foot', 'feet'],
        ['goose', 'geese'],
        ['tooth', 'teeth'],
        ['quiz', 'quizzes'],
        ['person', 'people'],
        ['man', 'men'],
        ['woman', 'women'],
        ['sheep', 'sheep'],
        ['category', 'categories'],

        // Spanish
        ['persona', 'personas'],
        ['categoría', 'categorías'],
    ];

    /**
     * String
     */
    public function __construct(
        private string $string
    ) {
        $this->original = $this->string;
    }

    public function resetOriginal(): static
    {
        $this->string = $this->original;
        return $this;
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

    /**
     * Lleva a mayúscula la primera letra de cada palabra
     *
     * @return static PascalCase
     */
    public function pascalCase(): static
    {
        $this->string = str_replace(
            ' ',
            '',
            ucwords(str_replace(['-', '_'], ' ', $this->string))
        );

        return $this;
    }

    /**
     * Obtiene el string final
     */
    public function getString(): string
    {
        return $this->string;
    }

    /**
     * Revisa si dentro de la cadena de texto se encuentra una palabra,
     * si se pasa un arreglo, se determina si incluye alguna del parámetro.
     */
    public function contains(array|string $search): bool
    {
        $search = is_string($search) ? [$search] : $search;
        $contains = false;

        foreach ($search as $value) {
            if (str_contains($this->string, $value)) {
                $contains = true;
                break;
            }
        }

        return $contains;
    }

    /**
     * Reemplaza 1 o varias palabras en el string
     */
    public function replace(string|array $search, string|array $replace): static
    {
        $this->string = str_replace($search, $replace, $this->string);
        return $this;
    }

    /**
     * Lleva a mayúscula la primera letra de la cadena
     */
    public function upperFirst(): static
    {
        $this->string = ucfirst($this->string);
        return $this;
    }

    /**
     * Llevar a minúscula la cadena
     */
    public function lower(): static
    {
        $this->string = strtolower($this->string);
        return $this;
    }

    /**
     * Elimina los espacios en blanco al inicio y final de la cadena,
     * o cualquier carácter especificado.
     */
    public function trimEnd(string $characters = " \n\r\t\v\0"): static
    {
        $this->string = rtrim($this->string, $characters);
        return $this;
    }

    public function normalize(int $from = Normalizer::FORM_D): static
    {
        $this->string = Normalizer::normalize($this->string, $from);
        return $this;
    }

    /**
     * Pluraliza el string
     *
     * @return static
     */
    public function pluralize(): static
    {
        return $this;
    }
}
