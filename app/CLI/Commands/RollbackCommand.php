<?php

namespace Cardikit\CLI\Commands;

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
        $migrations = glob(__DIR__ . '/../../../database/migrations/*.php');

        foreach ($migrations as $file) {
            echo "Rolling back: " . basename($file) . "\n";

            $migration = require $file;
            $migration->down();

            echo "↩ Rolled back\n";
        }
    }
}

