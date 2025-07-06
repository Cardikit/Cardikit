<?php

namespace Cardikit\CLI\Commands;

class HelloCommand
{
    public function handle(array $args): void
    {
        $name = $args[0] ?? 'world';
        echo "👋 Hello, $name!\n";
    }
}

