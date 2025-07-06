<?php

namespace App\Core;

use App\Core\Database;

abstract class Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    abstract public function up(): void;

    /**
    * Reverse the migration.
    *
    * @return void
    */
    abstract public function down(): void;

    /**
    * Run raw SQL on the database.
    *
    * @param string $sql
    *
    * @return void
    */
    protected function execute(string $sql): void
    {
        Database::connect()->exec($sql);
    }
}
