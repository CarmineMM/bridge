<?php

namespace Core\Database\Complement;

use Core\Support\Collection;

trait Casts
{
    /**
     * Casts de las propiedades
     */
    protected array $casts = [];

    /**
     * Lista de casts predefinidos en el modelo
     */
    private array $definedCasts = [
        'json'       => 'castJson',
        'collection' => 'castCollection',
        'datetime'   => 'castDatetime'
    ];

    /**
     * Aplicar casts
     */
    protected function getApplyCast(mixed $cast, mixed $item, $from = 'get'): mixed
    {
        if (is_string($cast) && array_key_exists($cast, $this->definedCasts)) {
            $castMethod = $this->definedCasts[$cast];
            return $this->$castMethod()[$from]($item);
        }

        if (is_callable($cast)) {
            return $cast()[$from]($item);
        }

        if (is_array($cast)) {
            if (!class_exists($cast[0])) {
                throw new \Exception("Class {$cast[0]} not found", 500);
            }

            $class = new $cast[0]();

            if (count($cast) === 1) {
                return $class->__invoke()[$from]($item);
            }

            return $class->{$cast[1]}()[$from]($item);
        }

        return $item;
    }

    /**
     * Cast a JSON
     */
    private function castJson(): array
    {
        return [
            'get'  => fn ($value) => json_decode($value, true),
            'set' => fn ($value) => json_encode($value, true),
        ];
    }

    /**
     * Cast a Collection
     */
    private function castCollection(): array
    {
        return [
            'get'  => fn ($value) => new Collection(json_decode($value, true), false),
            'set' => fn ($value) => $value instanceof Collection
                ? json_encode($value->all(), true)
                : json_encode($value, true),
        ];
    }

    /**
     * Cast a Datetime
     */
    private function castDatetime(): array
    {
        return [
            'get' => fn ($value) => new \DateTime($value),
            'set' => fn ($value) => $value->format('Y-m-d H:i:s')
        ];
    }
}
