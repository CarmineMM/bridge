<?php

namespace Core\Database\Base;

use Core\Database\CreatorColumn;
use Core\Implements\MigrateTable;

class CreateMigrationTable extends \Core\Database\Table implements MigrateTable
{
    public function up(): MigrateTable
    {
        return $this->table('migrations')
            ->column(fn (CreatorColumn $column) => $column->id())
            ->column(fn (CreatorColumn $column) => $column->string('migration')->comment('nombre de la migración'))
            ->column(fn (CreatorColumn $column) => $column->string('batch', 100)->comment('lote de la migración'));
    }

    public function down(): MigrateTable
    {
        return $this->dropTable('migrations');
    }
}
