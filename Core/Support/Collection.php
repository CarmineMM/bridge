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
     * @param array|object $data Elementos mutables dentro de la clase
     * @param bool $canBeRefactor Defina si se puede restablecer con "origin"
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
     * @param bool $overwrite Defina si se puede sobrescribir
     * @return $this
     */
    public function add(string $key, mixed $value, bool $overwrite = false): Collection
    {
        $wasGet = $this->has($key);

        if ($wasGet && strpos($key, '.') === false) {
            $this->data[$key][] = $value;
        } else if ($wasGet && $overwrite) {
            $this->data[$key] = $value;
        } else if (strpos($key, '.') !== false) {
            $route = explode('.', $key);
            $current = &$this->data;

            foreach ($route as $segment) {
                if (!isset($current[$segment])) {
                    $current[$segment] = [];
                }
                $current = &$current[$segment];
            }

            if (is_array($current)) {
                $current[] = $value;
            } else {
                $current = $value;
            }
        }

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

        foreach ($this->data as $key => $value) {
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

    /**
     * Actualiza un elemento de la colección
     */
    public function update(string $key, mixed $value): Collection
    {
        return $this;
    }

    /**
     * Convertir a atributos HTML
     *
     * @return string
     */
    public function toHTMLAttributes(): string
    {
        $attributes = '';

        foreach ($this->data as $key => $value) {
            if (!is_string($value)) {
                continue;
            }

            $attributes .= "{$key}=\"$value\" ";
        }

        return trim($attributes, ' ');
    }

    /**
     * Busca en los elementos una coincidencia,
     * si no la encuentra regresa null
     * 
     * @return Collection
     */
    public function where(string $name, mixed $value): static
    {
        $finds = [];

        if ($this->isAssoc()) {
            $get = $this->get($name);

            if ($get === $value) {
                $finds[] = $get;
            }
        }

        foreach ($this->data as $item) {
            if (isset($item[$name]) && $item[$name] === $value) {
                $finds[] = $item;
            }
        }

        return new Collection($finds);
    }

    /**
     * Realiza un conteo en los datos
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Obtiene el primer elemento
     */
    public function first(): static
    {
        return new Collection($this->data[0] ?? [], false);
    }

    /**
     * Filter elements
     * 
     * @param callable $call ($value, $key) => bool
     * @return static
     */
    public function filter(callable $call): static
    {
        $this->data = array_filter($this->data, $call, ARRAY_FILTER_USE_BOTH);
        return $this;
    }

    /**
     * JSON
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->data, $options);
    }
}
