<?php

namespace Core\Foundation;

/**
 * El contexto sera el encargado de almacenar los datos persistentes por sesión y dispositivo.
 * Estos pueden perdurar por un tiempo determinado o hasta que se cierre la sesión.
 * También están los de un solo uso, que se eliminan una vez que son Leidos.
 * 
 * @author Carmine Maggio <carminemaggiom@gmail.com>
 * @package Bridge
 * @version 1.0.0
 */
class Context
{
    /**
     * Name space para no confundir con otros datos de la sesión
     *
     * @var string
     */
    private string $namespace = 'context';

    /**
     * Key para los datos de un solo uso
     *
     * @var string
     */
    private string $keyStateContext = 'flash';

    /**
     * Key para los datos persistentes
     *
     * @var string
     */
    private string $keyStoreContext = 'persistent';

    /**
     * Establece un valor persistente en el contexto
     *
     * @param string $key
     * @param string|boolean|array $value El valor solo puede ser un dato primitivo
     * @return Context
     */
    public function setStore(string $key, string|bool|array $value): Context
    {
        $_SESSION[$this->namespace][$this->keyStoreContext][$key] = $value;
        return $this;
    }

    /**
     * Obtiene un valor no persistente y de un solo uso en el contexto 
     *
     * @param string $key
     * @param string|boolean|array $value El valor solo puede ser un dato primitivo
     * @return Context
     */
    public function setState(string $key,  string|bool|array $value): Context
    {
        $_SESSION[$this->namespace][$this->keyStateContext][$key] = $value;
        return $this;
    }

    /**
     * Obtiene un valor del store del contexto
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getStore(string $key, mixed $default): mixed
    {
        return $_SESSION[$this->namespace][$this->keyStoreContext][$key] ?? $default;
    }

    /**
     * Obtiene un valor del state del contexto,
     * luego se elimina el valor.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getState(string $key, mixed $default): mixed
    {
        $value = $_SESSION[$this->namespace][$this->keyStateContext][$key] ?? $default;
        unset($_SESSION[$this->namespace][$this->keyStateContext][$key]);
        return $value;
    }

    /**
     * Elimina del store un valor
     */
    public function deleteStore(string $key): Context
    {
        unset($_SESSION[$this->namespace][$this->keyStoreContext][$key]);
        return $this;
    }

    /**
     * Elimina un valor del state
     */
    public function deleteState(string $key): Context
    {
        unset($_SESSION[$this->namespace][$this->keyStateContext][$key]);
        return $this;
    }
}
