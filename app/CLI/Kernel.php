<?php

namespace Cardikit\CLI;

class Kernel
{
    protected array $commands = [
        'test' => Commands\TestCommand::class,
        // add more here
    ];

    public function handle(array $argv): void
    {
        $cmd = $argv[1] ?? null;

        if (!$cmd || !isset($this->commands[$cmd])) {
            $this->listCommands();
            return;
        }

        $class = $this->commands[$cmd];
        (new $class())->handle(array_slice($argv, 2));
    }

    protected function listCommands(): void
    {
        echo "Cardikit CLI\n\n";
        echo "Available commands:\n";
        foreach ($this->commands as $name => $class) {
            echo "  $name\n";
        }
        echo "\n";
    }
}

