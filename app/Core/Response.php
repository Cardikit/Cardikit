<?php

namespace App\Core;

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
}
