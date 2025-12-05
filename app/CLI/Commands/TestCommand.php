<?php

namespace Cardikit\CLI\Commands;

/**
* Runs PHP tests
*
* @package Cardikit\CLI\Commands
*
* @since 0.0.1
*/
class TestCommand
{
    /**
    * Executes PHP tests by calling the `pest` command.
    *
    * @param array $args Command line arguments
    *
    * @return void
    *
    * @since 0.0.1
    */
    public function handle(array $args): void
    {
        echo "🧪 Running PHP tests...\n";
        $cmd = ['./vendor/bin/pest', '--colors=always'];

        foreach($args as $arg) {
            $cmd[] = $arg;
        }

        $commandLine = implode(' ', array_map('escapeshellarg', $cmd));

        passthru($commandLine, $exitCode);
        exit($exitCode);
    }
}

