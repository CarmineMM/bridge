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
            'value' => 1,
            'name' => 'milisecond',
            'known'  => ['milisecond', 'miliseconds'],
            'symbol' => 'ms',
        ],
        's' => [
            'value' => 1000,
            'name' => 'second',
            'known'  => ['second', 'seconds'],
            'symbol' => 's',
        ],
        'm' => [
            'value' => 60000,
            'name' => 'minute',
            'known'  => ['minute', 'minutes'],
            'symbol' => 'm',
        ],
        'h' => [
            'value' => 3600000,
            'name' => 'hour',
            'known'  => ['hour', 'hours'],
            'symbol' => 'h',
        ],
        'd' => [
            'value' => 86400000,
            'name' => 'day',
            'known'  => ['day', 'days'],
            'symbol' => 'd',
        ],
        'w' => [
            'value' => 604800000000,
            'name' => 'week',
            'known'  => ['week', 'weeks'],
            'symbol' => 'w',
        ],
        'mth' => [
            'value' => 2629743000,
            'name' => 'month',
            'known'  => ['month', 'months'],
            'symbol' => 'mth',
        ],
        'y' => [
            'value' => 31556926000,
            'name' => 'year',
            'known'  => ['year', 'years'],
            'symbol' => 'y',
        ],
    ];
}
