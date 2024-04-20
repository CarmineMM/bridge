<?php

namespace Database\Migrations;

return new class extends \Core\Database\Table implements \Core\Implements\MigrateTable
{
    public function up(): void
    {
        $this->table('users')
            ->column(
                fn ($column) => $column->bigInt('id')->primaryKey()
            );
    }

    public function down(): void
    {
        # code...
    }
};
