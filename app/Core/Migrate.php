<?php

namespace App\Core;

class Migrate
{
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
