<?php

namespace Core\FullBridge;

class Lifecycle
{
    /**
     * Llama una función de un componente si existe
     */
    public static function callIf(mixed $component, string $call): void
    {
        if (method_exists($component, $call)) {
            $component->$call();
        }
    }

    public static function mount(mixed $component): void
    {
        static::callIf($component, 'mount');
    }

    public static function update(mixed $component): void
    {
        static::callIf($component, 'update');
    }
}
