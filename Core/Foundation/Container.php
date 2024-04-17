<?php

namespace Core\Foundation;

/**
 * El contenedor de la aplicación, se encarga de manejar los diferentes eventos que puedan ocurrir,
 * ademas adquiere las propiedades prescindibles de los servicios.
 * 
 * @package Bridge
 * @version 1.0.0
 * @author Carmine Maggio <carminemaggiom@gmail.com>
 */
class Container
{
    /**
     * Instancia del contenedor
     *
     * @var Container|null
     */
    private static ?Container $instance = null;

    /**
     * Observadores de los modelos
     */
    private array $observers = [];

    /**
     * Construye el contenedor
     *
     * @return Container
     */
    public static function make(array $props): Container
    {
        if (is_null(self::$instance)) {
            $self = new self();
            self::$instance = $self;
        } else {
            $self = self::$instance;
        }

        $self->setProps($props);
        return self::$instance;
    }

    /**
     * Establece las props
     */
    public function setProps(array $props): void
    {
        $this->observers = array_merge($this->observers, $props['observers'] ?? []);
    }

    /**
     * Obtiene los observadores de la aplicación
     */
    public static function getObservers(): array
    {
        return self::$instance->observers;
    }
}
