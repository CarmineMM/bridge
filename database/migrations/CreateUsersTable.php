<?php

namespace Database\Migrations;

use Core\Implements\MigrateTable;
use Core\Database\CreatorColumn;

class CreateUsersTable extends \Core\Database\Table implements MigrateTable
{
    public function up(): MigrateTable
    {
        return $this->table('users')
            ->column(fn (CreatorColumn $column) => $column->id());
    }

    public function down(): MigrateTable
    {
        return $this->dropTable('users');
    }
}
