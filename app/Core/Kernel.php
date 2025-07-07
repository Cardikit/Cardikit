<?php

namespace App\Core;

class Kernel
{
    protected array $middleware = [];

    public function middleware(string $route, array $middleware): void
    {
        $this->middleware[$route] = $middleware;
    }

    public function handle(array $argv): void
    {
        $route = $arv[1] ?? '/';
        $request = new Request();

        $middlewareStack = $this->middleware[$route] ?? [];

        $handler = function ($request) use ($route) {
            //
        };

        foreach (array_reverse($middlewareStack) as $middlewareClass) {
            $handler = fn($req) => (new $middlewareClass())->handle($req, $handler);
        }

        $handler($request);
    }
}
