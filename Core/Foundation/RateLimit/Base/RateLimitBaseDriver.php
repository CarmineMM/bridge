<?php

namespace Core\Foundation\RateLimit\Base;

class RateLimitBaseDriver
{
    /**
     * Cantidad de tiempo por el que se hacen las comprobaciones
     */
    protected int $timeRoadmap = 60 * 60; # Por minuto

    /**
     * Construct
     */
    public function __construct(
        /**
         * Limite por Minuto
         */
        protected int $limit,
        /**
         * Tiempo de ban
         */
        protected int $banTime,
    ) {
        //...
    }
}
