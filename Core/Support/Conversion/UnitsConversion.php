<?php

namespace Core\Support\Conversion;

/**
 * Conversion de unidades
 *
 * @author Carmine Maggio <carminemaggiom@gmail.com>
 * @package UnitsConversion
 * @version 2.0.0
 */
class UnitsConversion extends BaseConversion
{
    /**
     * Unidades conocidas
     */
    protected array $lists = [
        'bit' => [
            'name'   => 'Bit',
            'value'  => 1,
            'symbol' => 'bits',
            'known'  => ['bit', 'bits'],
        ],
        'byte' => [
            'name'   => 'Byte',
            'value'  => 8, // bit: 8
            'symbol' => 'bytes',
            'known'  => ['b', 'byte', 'bytes'],
        ],
        'KB' => [
            'name'   => 'Kilobyte',
            'value'  => 8192, // 1 kilobyte = 1024 bytes = 8192 bits
            'symbol' => 'KB',
            'known'  => ['kb', 'kilobyte', 'kilobytes'],
        ],
        'MB' => [
            'name'   => 'Megabyte',
            'value'  => 8 * (1024 ** 2), // bit: 8.388.608
            'symbol' => 'MB',
            'known'  => ['mb', 'megabyte', 'megabytes'],
        ],
        'GB' => [
            'name'   => 'Gigabyte',
            'value'  => 8 * (1024 ** 3), // bit: 8.589.934.592
            'symbol' => 'GB',
            'known'  => ['gb', 'gigabyte', 'gigabytes'],
        ],
        'TB' => [
            'name'   => 'Terabyte',
            'value'  => 8 * (1024 ** 4), // bit: 8.796.093.022.208
            'symbol' => 'TB',
            'known'  => ['tb', 'terabyte', 'terabytes'],
        ],
        'PB' => [
            'name'   => 'Petabyte',
            'value'  => 8 * (1024 ** 5), // bit: --
            'symbol' => 'PB',
            'known'  => ['pb', 'petabyte', 'petabytes'],
        ],
    ];

    /**
     * Construct
     *
     * @param string|integer $number
     * @param string $unit
     */
    public function __construct(string|int $number = 0, string $unit = '')
    {
        if ($number === 0) return;

        [$number, $unit] = $this->discoverUnit($number, $unit);

        $this->currentValue = $this->originalValue = static::convert($number, $unit, 'bit');
    }

    /**
     * Descubre la unidad de medida de un numero o de una cadena
     *
     * @param string|integer $number
     * @param string $unit
     * @return array
     */
    private function discoverUnit(string|int $number, string $unit = ''): array
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

        return [0, 'bit'];
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
            throw new \Exception('Unidad desconocida');
        }

        return $this->currentValue / $value;
    }

    /**
     * Muestra el valor en una unidad especifica,
     * en un string.
     *
     * @param string $unit
     * @return string
     */
    public function display(string $unit = 'bit', $decimals = 2): string
    {
        $unitKey = $this->getKeyUnit($unit);

        if ($unitKey === '') {
            throw new \Exception('Unidad desconocida');
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

        $this->currentValue += static::convert($number, $unit, 'bit');

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

        $this->currentValue -= static::convert($number, $unit, 'bit');

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

        return $this->currentValue > static::convert($number, $unit, 'bit');
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

        return $this->currentValue < static::convert($number, $unit, 'bit');
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

        return $this->currentValue >= static::convert($number, $unit, 'bit');
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

        $is = static::convert($number, $unit, 'bit');

        return $this->currentValue >= $is && $this->currentValue <= $is;
    }

    /**
     * Origin value en bits
     *
     * @return integer
     */
    public function origin(): int
    {
        return $this->originalValue;
    }

    /**
     * Original a una conversion nueva
     *
     * @return UnitsConversion
     */
    public function originTo(): UnitsConversion
    {
        return new static($this->originalValue, 'bit');
    }

    /**
     * Muestra el valor en string, convertido de forma inteligente,
     * en otras palabras siempre mostrar un numero de fácil lectura
     *
     * @return string
     */
    public function show(): string
    {
        return match (true) {
            $this->currentValue > $this->lists['PB']['value'] => $this->display('PB'),
            $this->currentValue > $this->lists['TB']['value'] => $this->display('TB'),
            $this->currentValue > $this->lists['GB']['value'] => $this->display('GB'),
            $this->currentValue > $this->lists['MB']['value'] => $this->display('MB'),
            $this->currentValue > $this->lists['KB']['value'] => $this->display('KB'),
            $this->currentValue > $this->lists['byte']['value'] => $this->display('byte'),
            default => $this->display('bit'),
        };
    }
}
