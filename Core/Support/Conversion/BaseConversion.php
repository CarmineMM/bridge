<?php

namespace Core\Support\Conversion;

/**
 * Conversion base (Compatible con otros tipos de conversiones matemáticas)
 * 
 * @author Carmine Maggio <carminemaggiom@gmail.com>
 * @package UnitsConversion
 * @version 1.0.0
 */
class BaseConversion
{
    /**
     * Valor establecido en el valor mas bajo
     */
    protected float $originalValue = 0;

    /**
     * Valor actual en en el valor mas bajo
     */
    protected float $currentValue = 0;

    /**
     * Lista de elementos
     */
    protected array $lists = [];

    /**
     * Primera clave de la lista
     *
     * @var string
     */
    private string $firstKey = '';

    /**
     * Construct
     *
     * @param string|integer $number
     * @param string $unit
     */
    public function __construct(string|int|float $number = 0, string $unit = '')
    {
        if ($number === 0) return;

        $this->firstKey = array_keys($this->lists)[0];

        [$number, $unit] = $this->discoverUnit($number, $unit);


        $this->currentValue = $this->originalValue = static::convert($number, $unit, $this->firstKey);
    }

    /**
     * Descubre la unidad de medida de un numero o de una cadena
     *
     * @param string|integer $number
     * @param string $unit
     * @return array
     */
    protected function discoverUnit(string|int|float $number, string $unit = ''): array
    {
        if ($unit === '' && !is_string($number)) {
            $number = floatval($number);
        }

        if (is_numeric($number) && $unit !== '') {
            return [$number, $this->getKeyUnit(strtolower($unit))];
        }


        if (is_string($number) && $unit === '') {
            [$number, $unit] = explode(' ', $number);

            return [floatval($number), $this->getKeyUnit($unit)];
        }

        return [0, $this->firstKey];
    }

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

        return $this->firstKey;
    }

    /**
     * Convertir el valor a un otro tipo de unidad
     *
     * @param string $unit
     * @return float
     */
    public function to(string $unit): float
    {
        $value = 0;

        foreach ($this->lists as $key => $theUnit) {
            if ($unit === $key) {
                $value = $theUnit['value'];
                break;
            }

            if (in_array($unit, $theUnit['known'])) {
                $value = $theUnit['value'];
                break;
            }
        }

        if ($value === 0) {
            throw new \Exception('Unit not found', 500);
        }

        return $this->currentValue / $value;
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
    public static function convert(int|float $number, string $unit, string $unitTo): float
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
    public static function make(string|int|float $number = 0, string $unit = ''): static
    {
        return new static($number, $unit);
    }

    /**
     * Origin value en el valor mas bajo
     *
     * @return integer
     */
    public function origin(): int
    {
        return $this->originalValue;
    }

    /**
     * Muestra el valor en una unidad especifica,
     * en un string.
     *
     * @param string $unit
     * @return string
     */
    public function display(string $unit, $decimals = 2): string
    {
        $unitKey = $this->getKeyUnit($unit);

        if ($unitKey === '') {
            throw new \Exception('Unit not found', 500);
        }

        return round($this->to($unitKey), $decimals, PHP_ROUND_HALF_UP) . ' ' . $this->lists[$unitKey]['symbol'];
    }

    /**
     * Agrega un valor a la unidad actual
     *
     * @param string|integer $number
     * @param string $unit
     * @return UnitsConversion
     */
    public function add(string|int $number, string $unit = ''): UnitsConversion
    {
        [$number, $unit] = $this->discoverUnit($number, $unit);

        $this->currentValue += static::convert($number, $unit, $this->firstKey);

        return $this;
    }

    /**
     * Resta un valor a la unidad actual
     *
     * @param string|integer $number
     * @param string $unit
     * @return UnitsConversion
     */
    public function less(string|int $number, string $unit = ''): UnitsConversion
    {
        [$number, $unit] = $this->discoverUnit($number, $unit);

        $this->currentValue -= static::convert($number, $unit, $this->firstKey);

        return $this;
    }


    /**
     * Compara si el valor actual es menor que el valor pasado
     *
     * @param string|integer $number
     * @param string $unit
     * @return boolean
     */
    public function isGreaterThan(string|int $number, string $unit = ''): bool
    {
        [$number, $unit] = $this->discoverUnit($number, $unit);

        return $this->currentValue > static::convert($number, $unit, $this->firstKey);
    }

    /**
     * Compara si el valor actual es menor que el valor pasado
     *
     * @param string|integer $number
     * @param string $unit
     * @return boolean
     */
    public function isLessThan(string|int $number, string $unit = ''): bool
    {
        [$number, $unit] = $this->discoverUnit($number, $unit);

        return $this->currentValue < static::convert($number, $unit, $this->firstKey);
    }

    /**
     * Compara si el valor actual es igual o menor al valor pasado
     *
     * @param string|integer $number
     * @param string $unit
     * @return boolean
     */
    public function isSameOrLess(string|int $number, string $unit = ''): bool
    {
        [$number, $unit] = $this->discoverUnit($number, $unit);

        return $this->currentValue <= static::convert($number, $unit, 'bit');
    }

    /**
     * Compara si el valor actual es igual o mayor al valor pasado
     *
     * @param string|integer $number
     * @param string $unit
     * @return boolean
     */
    public function isSameOrGreater(string|int $number, string $unit = ''): bool
    {
        [$number, $unit] = $this->discoverUnit($number, $unit);

        return $this->currentValue >= static::convert($number, $unit, $this->firstKey);
    }

    /**
     * Compara si el valor actual es igual o mayor al valor pasado
     *
     * @param string|integer $number
     * @param string $unit
     * @return boolean
     */
    public function isBetween(string|int $number, string $unit = ''): bool
    {
        [$number, $unit] = $this->discoverUnit($number, $unit);

        $is = static::convert($number, $unit, $this->firstKey);

        return $this->currentValue >= $is && $this->currentValue <= $is;
    }
}
