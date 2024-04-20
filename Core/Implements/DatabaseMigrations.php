<?php

namespace Core\Implements;

interface DatabaseMigrations
{
    /**
     * Indica las migraciones que deben ser ejecutadas
     */
    public function migrate(): array;
}
