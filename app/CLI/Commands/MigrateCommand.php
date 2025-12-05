<?php

namespace Cardikit\CLI\Commands;

use App\Core\Migrate;

/**
* Handles running all migration files.
*
* @package Cardikit\CLI\Commands
*
* @since 0.0.2
*/
class MigrateCommand
{
    /**
    * Executes all migration files by calling their `up()` method.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function handle(array $argv): void
    {
        Migrate::run();
    }
}
