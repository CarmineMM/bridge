<?php

namespace Database\Migrations;

use Core\Implements\MigrateTable;
use Core\Database\CreatorColumn;

class CreateUsersTable extends \Core\Database\Table implements MigrateTable
{
    protected string $table_name = 'users';

    public function up(): MigrateTable
    {
        return $this->table($this->table_name)
            ->column(fn(CreatorColumn $column) => $column->id())
            ->column(fn(CreatorColumn $column) => $column->string('name'))
            ->column(fn(CreatorColumn $column) => $column->string('email')->unique())
            ->column(fn(CreatorColumn $column) => $column->string('password', 160))
            ->column(fn(CreatorColumn $column) => $column->timestamps());
    }

    public function down(): MigrateTable
    {
        return $this->dropTable($this->table_name);
    }
}
