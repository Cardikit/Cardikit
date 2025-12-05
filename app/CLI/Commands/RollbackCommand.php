<?php

namespace Cardikit\CLI\Commands;

use App\Core\Migrate;

/**
* Handles rolling back all migration files.
*
* @package Cardikit\CLI\Commands
*
* @since 0.0.1
*/
class RollbackCommand
{
    /**
    * Executes all migration files by calling their `down()` method.
    *
    * @return void
    *
    * @since 0.0.1
    */
    public function handle(): void
    {
        Migrate::rollback();
    }
}
