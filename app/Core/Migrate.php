<?php

namespace App\Core;

/**
* Runs migration utilities on all migration files.
*
* @package App\Core
*
* @since 0.0.1
*/
class Migrate
{
    /**
    * Runs all up methods in migration files.
    *
    * @return void
    *
    * @since 0.0.1
    */
    public static function run(): void
    {
        $migrations = glob(__DIR__ . '/../../database/migration/*.php');

        foreach ($migrations as $file) {
            echo "Running: " . basename($file) . "\n";

            $migration = require $file;
            $migration->up();

            echo "✔ Done\n";
        }
    }

    /**
    * Runs all down methods in migration files.
    *
    * @return void
    *
    * @since 0.0.1
    */
    public static function rollback(): void
    {
        $migrations = glob(__DIR__ . '../../database/migrations/*.php');

        foreach ($migrations as $file) {
            echo "Rolling back: " . basename($file) . "\n";

            $migration = require $file;
            $migration->down();

            echo "↩ Rolled back\n";
        }
    }
}
