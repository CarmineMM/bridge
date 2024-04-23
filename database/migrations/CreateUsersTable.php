<?php

namespace Database\Migrations;

use Core\Database\CreatorColumn;
use Core\Implements\MigrateTable;

class CreateUsersTable extends \Core\Database\Table implements \Core\Implements\MigrateTable
{
    public function up(): MigrateTable
    {
        return $this->table('users')
            ->column(fn (CreatorColumn $column) => $column->id())
            ->column(fn (CreatorColumn $column) => $column->string('name', 100)->comment('nombre del usuario'));
    }

    public function down(): MigrateTable
    {
        return $this->table('users');
    }
}
