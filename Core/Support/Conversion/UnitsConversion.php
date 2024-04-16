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
