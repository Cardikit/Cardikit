<?php

namespace App\Core;

/**
* Handles and parses incoming HTTP requests, providing access to
* method, URI, headers, query parameters, and parsed body.
*
* @package App\Core
* @since 0.0.1
*/
class Request
{
    /**
    * The HTTP request method
    *
    * @var string $method
    *
    * @since 0.0.1
    */
    protected string $method;

    /**
    * The requested URI path
    *
    * @var string $uri
    *
    * @since 0.0.1
    */
    protected string $uri;

    /**
    * The request headers
    *
    * @var array $headers
    *
    * @since 0.0.1
    */
    protected array $headers;

    /**
    * The request query parameters
    *
    * @var array $queryParams
    *
    * @since 0.0.1
    */
    protected array $queryParams;

    /**
    * The request body
    *
    * @var array $body
    *
    * @since 0.0.1
    */
    protected array $body;

    /**
    * Initializes the request object by capturing method, URI, headers, 
    * query parameters, and request body.
    *
    * @since 0.0.1
    */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->headers = $this->getHeaders();
        $this->queryParams = $_GET;
        $this->body = $this->parseInput();
    }

    /**
    * Gets the request headers.
    * While testing, the getallheaders function is
    * unavailable, so a custom implementation is used.
    *
    * @return array The request headers.
    *
    * @since 0.0.1
    */
    public function getHeaders(): array
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    /**
    * Parses the raw request body based on content type.
    * Supports JSON and fallback to URL-encoded data.
    *
    * @return array The parsed request body.
    *
    * @since 0.0.1
    */
    protected function parseInput(): array
    {
        if (isset($GLOBALS['__test_body'])) {
            return $GLOBALS['__test_body'];
        }

        $input = file_get_contents('php://input');
        $this->headers = array_change_key_case($this->headers, CASE_LOWER);
        $contentType = $this->headers['content-type'] ?? '';

        if (stripos($contentType, 'application/json') !== false) {
            return json_decode($input, true) ?? [];
        }

        parse_str($input, $parsed);
        return $parsed;
    }

    /**
    * Get the HTTP request method
    *
    * @return string
    *
    * @since 0.0.1
    */
    public function method(): string
    {
        return $this->method;
    }

    /**
    * Get the requested URI path
    *
    * @return string
    *
    * @since 0.0.1
    */
    public function uri(): string
    {
        return rtrim($this->uri, '/') ?: '/';
    }

    /**
    * Get the request body
    *
    * @return array
    *
    * @since 0.0.1
    */
    public function body(): array
    {
        return $this->body;
    }

    /**
    * Get the request query parameters
    *
    * @return array
    *
    * @since 0.0.1
    */
    public function query(): array
    {
        return $this->queryParams;
    }

    /**
    * Get the request headers
    *
    * @return array
    *
    * @since 0.0.1
    */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
    * Get the ip address of the user
    *
    * @return string|null
    *
    * @since 0.0.1
    */
    public function ip(): ?string
    {
        return $_SERVER['REMOTE_ADDR'] ?? null;
    }
}
