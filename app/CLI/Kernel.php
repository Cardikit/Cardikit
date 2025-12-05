<?php

namespace Cardikit\CLI;

/**
* class Kernel represents the main entry point for the Cardikit CLI.
*
* @package Cardikit\CLI
*
* @since 0.0.1
*/
class Kernel
{
    /**
    * The list of available commands.
    *
    * @var array
    *
    * @since 0.0.1
    */
    protected array $commands = [
        'test' => Commands\TestCommand::class,
        'migrate' => Commands\MigrateCommand::class,
        'rollback' => Commands\RollbackCommand::class,
    ];

    /**
    * Handles the command line arguments.
    *
    * @param array $argv Command line arguments
    *
    * @return void
    *
    * @since 0.0.1
    */
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

    /**
    * Lists the available commands.
    *
    * @return void
    *
    * @since 0.0.1
    */
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

