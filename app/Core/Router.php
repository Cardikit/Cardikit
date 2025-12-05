<?php

namespace App\Core;

use App\Core\Response;
use App\Middleware\MiddlewareInterface;

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

    protected static array $middleware = [];

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
    public static function get(string $uri, callable|array $action, array $middleware = []): void
    {
        self::$routes['GET'][$uri] = $action;
        self::$middleware['GET'][$uri] = $middleware;
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
    public static function post(string $uri, callable|array $action, array $middleware = []): void
    {
        self::$routes['POST'][$uri] = $action;
        self::$middleware['POST'][$uri] = $middleware;
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
    public static function delete(string $uri, callable|array $action, array $middleware = []): void
    {
        self::$routes['DELETE'][$uri] = $action;
        self::$middleware['DELETE'][$uri] = $middleware;
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
    public static function put(string $uri, callable|array $action, array $middleware = []): void
    {
        self::$routes['PUT'][$uri] = $action;
        self::$middleware['PUT'][$uri] = $middleware;
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
        $middlewareMap = self::$middleware[$method] ?? [];
        $request = new Request();

        // Iterate over registered routes to find a match
        foreach ($routes as $route => $action) {
            // Convert route with :param to regex pattern
            $pattern = preg_replace('/:\w+/', '([^/]+)', $route);
            if ($route === '/') {
                $pattern = "#^/$#";
            } else {
                $pattern = "#^" . rtrim($pattern, '/') . "$#";
            }

            // If the current route matches
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match

                $middlewareList = $middlewareMap[$route] ?? [];
                foreach ($middlewareList as $middleware) {
                    if ($middleware instanceof MiddlewareInterface) {
                        if (!$middleware->handle($request)) {
                            return;
                        }
                        continue;
                    }

                    // Ignore unrecognized middleware types to avoid fatals
                    if (is_object($middleware) && method_exists($middleware, 'handle')) {
                        if (!$middleware->handle($request)) {
                            return;
                        }
                    }
                }

                // If route uses controller syntax [Controller::class, 'method']
                if (is_array($action)) {
                    [$controller, $method] = $action;
                    $controllerInstance = new $controller;
                    $response =  call_user_func_array([$controllerInstance, $method], array_merge([$request], $matches));
                } else {
                    $response = call_user_func_array($action, array_merge([$request], $matches));
                }

                // If handler returns array, return as JSON
                if (is_array($response)) {
                    Response::json($response);
                } elseif ($response !== null) {
                    echo $response;
                }

                return;
            }
        }

        // If no route matched, return a 404 with JSON error
        Response::json(['error' => 'Route not found'], 404);
    }
}
