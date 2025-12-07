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
}
