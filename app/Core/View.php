<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = []): void
    {
        $viewFile = dirname(__DIR__, 2) . '/views/' . $view . '.php';
        if (is_file($viewFile)) {
            Response::view($viewFile, $data);
            return;
        }

        // fallback error if page not found
        Response::html('page missing', 404);
    }
}
