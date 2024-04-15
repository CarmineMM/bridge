<?php

namespace Core\Support;

/**
 * Collections, una pequeña imitación a Collection de Laravel
 * 
 * @author Carmine Maggio <carminemaggiom@gmail.com>
 * @version 1.3.0
 */
class Collection
{
    /**
     * Original
     */
    private array $original = [];

    /**
     * Construct
     *
     * @param array $el Elementos mutables dentro de la clase
     * @param bool $convertAttributes Defina si se convierten los atributos a parte del objeto
     */
    public function __construct(
        public array|object $data = [],
        bool $canBeRefactor = true,
    ) {
        $this->data = $this->conditionalArray($this->data);

        if ($canBeRefactor) {
            $this->original = $this->data;
        }
    }

    /**
     * Make Collection
     */
    public static function make(array|object $data): Collection
    {
        return new Collection($data);
    }

    /**
     * Convertir a object de forma condicional
     */
    private function conditionalArray(mixed $to): array
    {
        return is_array($to) ? $to : (array) $to;
    }

    /**
     * Define si es un arreglo asociativo
     */
    public function isAssoc(): bool
    {
        $keys = array_keys($this->data);

        return array_keys($keys) !== $keys;
    }

    /**
     * Obtiene un elemento de la colección
     *
     * @param string $index Indice a buscar por dot notation
     * @param mixed $default Valor por defecto si no se encuentra el indice
     * @return mixed
     */
    public function get(string $index, mixed $default = null): mixed
    {
        $value = false;

        if (isset($this->data[$index])) {
            return $this->data[$index];
        }

        foreach (explode('.', $index) as $segment) {
            if ($value ? isset($value[$segment]) : isset($this->data[$segment])) {
                $value = $value ? $value[$segment] : $this->data[$segment];
            } else {
                return $default;
            }
        }

        return $value;
    }

    /**
     * Delimita si existe un elemento
     */
    public function has(string $index): bool
    {
        return (bool) $this->get($index, false);
    }

    /**
     * Traer todos los elementos
     *
     * @return object
     */
    public function all(): object
    {
        return (object) $this->data;
    }

    /**
     * Regresa todos los valores en forma de array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Reset el objeto con el original.
     */
    public function reset(): Collection
    {
        $this->data = $this->original;

        return $this;
    }

    /**
     * Agrega un elemento a la colección
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function add(string $key, mixed $value): Collection
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Pasa sobre la collection
     *
     * @param callable $call
     * @return Collection
     */
    public function map(callable $call): Collection
    {
        $newObject = [];

        foreach ($this->toArray() as $key => $value) {
            $newObject[$key] = $call($value, $key);
        }

        $this->data = $this->conditionalArray($newObject);

        return $this;
    }

    /**
     * Colapsa el estado actual de la collection
     *
     * @return Collection
     */
    public function collapse(): Collection
    {
        $results = [];

        foreach ($this->data as $values) {
            if (!is_array($values)) {
                continue;
            }

            $results[] = $values;
        }

        $this->data = array_merge([], ...$results);

        return $this;
    }
}
