<?php

namespace Database;

use Core\Database\MigrationHandler;
use Database\Migrations\CreateUsersTable;

class Migrator extends MigrationHandler
{
    protected array $migrations = [
        CreateUsersTable::class,
    ];
}
