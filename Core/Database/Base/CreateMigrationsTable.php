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
            ->column(fn (CreatorColumn $column) => $column->string('migration')->comment('Nombre del archivo de migración'))
            ->column(fn (CreatorColumn $column) => $column->string('batch', 100)->comment('Lote de la migración'))
            ->column(fn (CreatorColumn $column) => $column->timestamp('created_at')->default('CURRENT_TIMESTAMP'));
    }

    public function down(): MigrateTable
    {
        return $this->dropTable('migrations');
    }
}
