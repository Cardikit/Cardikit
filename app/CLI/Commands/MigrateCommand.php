<?php

namespace Cardikit\CLI\Commands;

/**
* Handles running all migration files.
*
* @package Cardikit\CLI\Commands
*
* @since 1.0.0
*/
class MigrateCommand
{
    /**
    * Executes all migration files by calling their `up()` method.
    *
    * @return void
    *
    * @since 1.0.0
    */
    public function handle(array $argv): void
    {
        $migrations = glob(__DIR__ . '/../../../database/migrations/*.php');
        $migrations = array_reverse($migrations);

        foreach ($migrations as $file) {
            echo "Running: " . basename($file) . "\n";

            $migration = require $file;

            // Validate the migration object
            if (!is_object($migration) || !method_exists($migration, 'up')) {
                echo "⚠️  Skipping: invalid migration format in " . basename($file) . "\n";
                continue;
            }

            $migration->up();

            echo "✔ Done\n";
        }
    }
}

