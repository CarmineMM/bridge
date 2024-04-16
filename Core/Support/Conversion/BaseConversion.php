<?php

namespace Core\Support\Conversion;

class BaseConversion
{
    /**
     * Valor establecido en el valor mas bajo
     */
    protected int $originalValue = 0;

    /**
     * Valor actual en en el valor mas bajo
     */
    protected int $currentValue = 0;

    /**
     * Lista de elementos
     */
    protected array $lists = [];

    /**
     * Obtiene el key de la unidad de medida,
     * devolverá un string vació en caso de no existir.
     *
     * @param string $unit
     * @return string
     */
    protected function getKeyUnit(string $unit): string
    {
        $unit = strtolower($unit);

        if (isset($this->lists[$unit])) {
            return $unit;
        }

        foreach ($this->lists as $key => $value) {
            if (in_array($unit, $value['known'])) {
                return $key;
            }
        }

        return reset($this->lists);
    }

    /**
     * Unidades disponibles para la conversión
     *
     * @return array
     */
    public function getAvailableUnits(): array
    {
        return $this->lists;
    }

    /**
     * Convierte a una unidad especifica, pero debe ser una de las listada.
     * Si no es una de las listadas, el método puede dar error.
     *
     * @return integer
     */
    public static function convert(int $number, string $unit, string $unitTo): float
    {
        $self = new static;

        $unit = $self->getAvailableUnits()[$unit];
        $unitTo = $self->getAvailableUnits()[$unitTo];

        return ($number * $unit['value']) / $unitTo['value'];
    }

    /**
     * Reset current value
     *
     * @return static
     */
    public function reset(): static
    {
        $this->currentValue = $this->originalValue;

        return $this;
    }

    /**
     * Make units conversion
     *
     * @param integer $number
     * @param string $unit
     * @return static
     */
    public static function make(string|int $number = 0, string $unit = ''): static
    {
        return new static($number, $unit);
    }
}
