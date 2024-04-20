<?php

namespace Database;

use Core\Database\MigrationHandler;
use Core\Implements\DatabaseMigrations;

class Migrations extends MigrationHandler implements DatabaseMigrations
{
    /**
     * Indica las migraciones que deben ser ejecutadas y el orden
     */
    public function run(): array
    {
        return [];
    }
}
