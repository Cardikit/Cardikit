<?php
/**
 * Cardikit
 *
 * A modern, framework-lite PHP application for creating, managing,
 * and sharing digital business cards.
 *
 * Cardikit is built to feel like a focused, developer-friendly toolkit
 * rather than a full-blown framework. It gives you just enough structure
 * to build and ship a fast, SPA-powered digital business card platform,
 * without locking you into heavy conventions or magic.
 *
 * Core concepts:
 *  - Clean MVC-style architecture with a simple front controller.
 *  - A lightweight router for mapping HTTP requests to controllers.
 *  - A small, focused core so your app logic and UI stay in your hands.
 *  - SPA entry points for a smooth, app-like dashboard experience.
 *
 * Typical usage:
 *  - Render marketing/landing pages from classic PHP routes.
 *  - Serve a JavaScript SPA for authenticated users under /app.
 *  - Generate and manage digital business cards, QR codes, and related data.
 *
 * This file acts as the primary front controller:
 *  - Serves static assets from the /public directory when requested directly.
 *  - Boots the application stack and router for all other requests.
 *  - Hands off /app routes to the SPA controller for client-side rendering.
 *
 * @package   Cardikit
 * @author    Cardikit Contributors
 * @copyright Copyright (c) Cardikit
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link      https://cardikit.com
 * @since     0.0.2
 * @filesource
 */

use App\Core\Router;
use App\Controllers\SpaController;

require __DIR__ . '/../bootstrap.php';

$publicPath = __DIR__;
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$candidateFile = realpath($publicPath . $requestPath);

// Serve built assets directly if they exist under /public
if ($candidateFile && str_starts_with($candidateFile, realpath($publicPath)) && is_file($candidateFile)) {
    $mime = mime_content_type($candidateFile) ?: 'application/octet-stream';
    header('Content-Type: ' . $mime);
    readfile($candidateFile);
    return;
}

// Serve SPA for any /app* path (falls back to /public/app or /public/dist)
if (str_starts_with($requestPath, '/app')) {
    (new SpaController())->show();
    return;
}

require dirname(__DIR__) . '/routes/web.php';

Router::dispatch();
