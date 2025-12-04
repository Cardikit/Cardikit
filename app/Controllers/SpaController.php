<?php

namespace App\Controllers;

/**
 * Serves the compiled single-page application for app routes.
 *
 * Looks for a built index.html under /public/app (preferred) or /public/dist
 * and streams it back with the correct content type.
 */
class SpaController
{
    public function show(): void
    {
        $publicPath = dirname(__DIR__, 2) . '/public';
        $candidates = [
            $publicPath . '/app/index.html',
            $publicPath . '/dist/index.html',
        ];

        foreach ($candidates as $file) {
            if (is_file($file)) {
                header('Content-Type: text/html; charset=utf-8');
                readfile($file);
                return;
            }
        }

        http_response_code(500);
        echo 'App build not found. Please run the frontend build and place it in public/app or public/dist.';
    }
}
