<?php

namespace App\Controllers;

/**
 * Serves the compiled single-page application for app routes.
 *
 * Looks for a built index.html under /public/app (preferred) or /public/dist
 * and streams it back with the correct content type.
 *
 * @package App\Controllers
 *
 * @since 0.0.2
 */
class SpaController
{
    /**
    * Serves the compiled single-page application for app routes.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function show(): void
    {
        // Create path for index.html candidates
        $publicPath = dirname(__DIR__, 2) . '/public';
        $candidates = [
            $publicPath . '/app/index.html',
            $publicPath . '/dist/index.html',
        ];

        // Serve index.html
        foreach ($candidates as $file) {
            if (is_file($file)) {
                header('Content-Type: text/html; charset=utf-8');
                readfile($file);
                return;
            }
        }

        // No index.html found response
        http_response_code(500);
        echo 'App build not found. Please run the frontend build and place it in public/app or public/dist.';
    }
}
