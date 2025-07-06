<?php

namespace App\Core;

/**
* Handles incoming requests and dispatches them to the appropriate handlers based on the HTTP method and URI.
*
* @package App\Core
*
* @since 0.0.1
*/
class Router
{
    /**
    * Stores all registered routes grouped by HTTP method.
    *
    * @var array
    *
    * @since 0.0.1
    */
    protected static array $routes = [];

    /**
    * Handles GET requests.
    *
    * @param string $uri
    * @param callable|array $action
    *
    * @return void
    *
    * @since 0.0.1
    */
    public static function get(string $uri, callable|array $action): void
    {
        self::$routes['GET'][$uri] = $action;
    }

    /**
    * Handles POST requests.
    *
    * @param string $uri
    * @param callable|array $action
    *
    * @return void
    *
    * @since 0.0.1
    */
    public static function post(string $uri, callable|array $action): void
    {
        self::$routes['POST'][$uri] = $action;
    }

    /**
    * Handles DELETE requests.
    *
    * @param string $uri
    * @param callable|array $action
    *
    * @return void
    *
    * @since 0.0.1
    */
    public static function delete(string $uri, callable|array $action): void
    {
        self::$routes['DELETE'][$uri] = $action;
    }

    /**
    * Handles PUT requests.
    *
    * @param string $uri
    * @param callable|array $action
    *
    * @return void
    *
    * @since 0.0.1
    */
    public static function put(string $uri, callable|array $action): void
    {
        self::$routes['PUT'][$uri] = $action;
    }

    /**
    * Parses the request, matches it against defined routes,
    * extracts parameters, and dispatches the request to the appropriate handler.
    *
    * Supports both closure-based and controller method routes.
    * If the handler returns an array, it will be encoded and sent as a JSON response.
    * Returns 404 JSON error if no route matches.
    *
    * @return void
    *
    * @since 0.0.1
    */
    public static function dispatch(): void
    {
        // Get the HTTP method and requested URI path
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        // Get the routes for the current method
        $routes = self::$routes[$method] ?? [];

        // Iterate over registered routes to find a match
        foreach ($routes as $route => $action) {
            // Convert route with :param to regex pattern
            $pattern = preg_replace('/:\w+/', '([^/]+)', $route);
            $pattern = "#^" . rtrim($pattern, '/') . "$#";

            // If the current route matches
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match

                // If route uses controller syntax [Controller::class, 'method']
                if (is_array($action)) {
                    [$controller, $method] = $action;
                    $controllerInstance = new $controller;
                    $response =  call_user_func_array([$controllerInstance, $method], $matches);
                } else {
                    $response = call_user_func_array($action, $matches);
                }

                // If handler returns array, return as JSON
                if (is_array($response)) {
                    header('Content-Type: application/json');
                    echo json_encode($response);
                } elseif ($response !== null) {
                    echo $response;
                }

                return;
            }
        }

        // If no route matched, return a 404 with JSON error
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}
