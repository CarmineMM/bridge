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
    private array $dictionary = [
        // Regla para palabras que terminan en 'a'
        '/a$/i' => [
            's' => 'as',
            'x' => 'ices',
            'z' => 'ces',
            'o' => 'oes',
            'e' => 'es',
            'i' => 'is',
            'u' => 'ues'
        ],
        // Regla para palabras que terminan en 'a'
        '/e$/i' => [
            's' => 'es',
            'x' => 'ices',
            'z' => 'ces',
            'o' => 'oes',
            'e' => 'es',
            'i' => 'ies',
            'u' => 'ues'
        ],
        // Regla para palabras que terminan en 'o'
        '/o$/i' => [
            's' => 'os',
            'x' => 'ices',
            'z' => 'ces',
            'o' => 'os',
            'e' => 'es',
            'i' => 'is',
            'u' => 'ues'
        ],
        // Regla para palabras que terminan en 'i'
        '/i$/i' => [
            's' => 'is',
            'x' => 'ices',
            'z' => 'ces',
            'o' => 'oes',
            'e' => 'es',
            'i' => 'is',
            'u' => 'ues'
        ],
        // Regla para palabras que terminan en 'u'
        '/u$/i' => [
            's' => 'ues',
            'x' => 'ices',
            'z' => 'ces',
            'o' => 'oes',
            'e' => 'es',
            'i' => 'is',
            'u' => 'ues'
        ],
        // Regla para palabras que terminan en 'n'
        '/n$/i' => [
            'a' => 'as',
            's' => 'es',
            'x' => 'ices',
            'z' => 'ces',
            'o' => 'ones',
            'e' => 'nes',
            'i' => 'nis',
            'u' => 'nues'
        ],
        // Regla para palabras que terminan en consonante
        '[^aeiou]$' => [
            's' => '$',
            'x' => 'ices',
            'z' => 'ces',
            'o' => '$',
            'e' => '$',
            'i' => '$',
            'u' => '$'
        ],
        // Regla para palabras que terminan en vocal seguida de consonante
        '[aeiou][^aeiou]$' => [
            's' => '$',
            'x' => 'ices',
            'z' => 'ces',
            'o' => '$',
            'e' => '$',
            'i' => '$',
            'u' => '$'
        ],
        // Regla para palabras que terminan en consonante seguida de vocal
        '[^aeiou][aeiou]$' => [
            's' => 'es',
            'x' => 'ices',
            'z' => 'ces',
            'o' => 'oes',
            'e' => 'es',
            'i' => 'is',
            'u' => 'ues'
        ]
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
     * Pluraliza el string.
     * Solo funciona con palabras en español.
     *
     * @return static
     */
    public function pluralize(): static
    {
        if (!in_array(config('app.locale'), ['es'])) {
            return $this;
        }

        // En ingles y español las palabras que terminen en "y" no tienen un plural y 
        // las que terminan en "s" tienen un plural por defecto
        if (str_ends_with($this->string, 'y') || str_ends_with($this->string, 's')) {
            return $this;
        }

        // Obtener la última letra de la palabra
        $last_letter = substr($this->string, -1);

        // Obtener el resto de la palabra
        $rest_word = substr($this->string, 0, -1);

        // Buscar la regla que se aplique a la palabra
        foreach ($this->dictionary as $regex => $plural) {
            if (preg_match($regex, $rest_word)) {
                $this->string = $rest_word . $plural[$last_letter];
                break;
            }
        }

        return $this;
    }
}
