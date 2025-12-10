<?php

namespace App\Core {

/**
* Handles sending HTTP responses in JSON format.
* Provides utility for setting proper headers and status codes.
*
* @package App\Core
*
* @since 0.0.1
*/
class Response
{
    /**
    * Sends a JSON-formatted HTTP response with appropriate headers and status code.
    *
    * @param array $data   The data to encode as JSON.
    * @param int   $status The HTTP response status code. Defaults to 200 (OK).
    *
    * @return void
    *
    * @since 0.0.1
    */
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
    * Sends an HTML response.
    *
    * @param string $content The HTML content to send.
    * @param int $status The HTTP response status code. Defaults to 200 (OK).
    * @param string $contentType The content type of the response. Defaults to 'text/html; charset=utf-8'.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public static function html(string $content, int $status = 200, string $contentType = 'text/html; charset=utf-8'): void
    {
        http_response_code($status);
        header('Content-Type: ' . $contentType);
        echo $content;
    }

    /**
    * Renders a PHP view file with provided data and sends it as HTML.
    *
    * @param string $path The path to the view file.
    * @param array $data An array of data to pass to the view.
    * @param int $status The HTTP response status code. Defaults to 200 (OK).
    *
    * @return void
    *
    * @since 0.0.2
    */
    public static function view(string $path, array $data = [], int $status = 200): void
    {
        if (!is_file($path)) {
            self::html('View not found', 500);
            return;
        }

        ob_start();
        extract($data, EXTR_OVERWRITE);
        include $path;
        $output = (string) ob_get_clean();

        self::html($output, $status);
    }

    /**
    * Render a 500 error page if available, otherwise generic text.
    */
    public static function serverError(): void
    {
        $view500 = dirname(__DIR__, 2) . '/views/500.php';
        if (is_file($view500)) {
            self::view($view500, [], 500);
            return;
        }
        self::html('Internal Server Error', 500);
    }
    }
}

namespace {
    if (!function_exists('esc')) {
        /**
        * Escape output for safe HTML rendering.
        *
        * @param mixed $value
        *
        * @return string
        */
        function esc(mixed $value): string
        {
            return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
        }
    }

    if (!function_exists('asset_url')) {
        /**
        * Build a public asset URL with a cache-busting version query.
        *
        * Uses ASSET_VERSION from .env when set; otherwise falls back to the
        * file's modification time under /public. If the file cannot be found,
        * the bare path is returned.
        *
        * @param string $path Relative path under /public.
        * @param string|null $version Optional explicit version override.
        */
        function asset_url(string $path, ?string $version = null): string
        {
            $normalizedPath = '/' . ltrim($path, '/');
            $publicRoot = realpath(dirname(__DIR__, 2) . '/public') ?: dirname(__DIR__, 2) . '/public';

            $versionString = $version ?? \App\Core\Config::get('ASSET_VERSION');

            if ($versionString === null || $versionString === '') {
                $candidate = realpath($publicRoot . $normalizedPath);
                if ($candidate && str_starts_with($candidate, $publicRoot) && is_file($candidate)) {
                    $versionString = (string) filemtime($candidate);
                }
            }

            if ($versionString === null || $versionString === '') {
                return $normalizedPath;
            }

            $separator = str_contains($normalizedPath, '?') ? '&' : '?';
            return $normalizedPath . $separator . 'v=' . rawurlencode($versionString);
        }
    }
}
