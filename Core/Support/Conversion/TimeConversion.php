<?php

namespace Core\Support\Conversion;

class TimeConversion extends BaseConversion
{
    /**
     * Listado de tiempos
     *
     * @var array
     */
    protected array $lists = [
        'ms' => [
            'value' => 0.001,
            'name' => 'milisecond',
            'known'  => ['milisecond', 'miliseconds'],
            'symbol' => 'ms',
        ],
        's' => [
            'value' => 1,
            'name' => 'second',
            'known'  => ['second', 'seconds'],
            'symbol' => 's',
        ],
        'm' => [
            'value' => 60,
            'name' => 'minute',
            'known'  => ['minute', 'minutes'],
            'symbol' => 'm',
        ],
        'h' => [
            'value' => 3600,
            'name' => 'hour',
            'known'  => ['hour', 'hours'],
            'symbol' => 'h',
        ],
        'd' => [
            'value' => 86400,
            'name' => 'day',
            'known'  => ['day', 'days'],
            'symbol' => 'd',
        ],
        'w' => [
            'value' => 604800,
            'name' => 'week',
            'known'  => ['week', 'weeks'],
            'symbol' => 'w',
        ],
        'y' => [
            'value' => 31556926,
            'name' => 'year',
            'known'  => ['year', 'years'],
            'symbol' => 'y',
        ],
    ];
}
