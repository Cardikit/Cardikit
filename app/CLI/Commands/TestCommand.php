<?php

namespace Cardikit\CLI\Commands;

class TestCommand
{
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

