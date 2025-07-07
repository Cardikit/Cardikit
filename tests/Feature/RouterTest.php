<?php

use App\Core\Router;
use App\Core\Request;

/**
* Simulates requests to the router
*
* @param string $method
* @param string $uri
* @param array $jsonBody
*
* @return string
*
* @since 0.0.1
*/
function simulateRequest(string $method, string $uri, array $body = []): string
{
    $_SERVER['REQUEST_METHOD'] = $method;
    $_SERVER['REQUEST_URI'] = $uri;
    $_SERVER['CONTENT_TYPE'] = 'application/json'; // simulate JSON content-type
    $_GET = [];
    $_POST = [];

    $GLOBALS['__test_body'] = $body;

    ob_start();
    \App\Core\Router::dispatch();
    $output = ob_get_clean();

    unset($GLOBALS['__test_body']);

    return $output;
}


// Reset routes between tests
beforeEach(function () {
    (new ReflectionClass(Router::class))->setStaticPropertyValue('routes', []);
});

// Basic GET request
test('GET /ping return pong', function () {
    Router::get('/ping', fn () => 'pong');

    $output = simulateRequest('GET', '/ping');

    expect($output)->toBe('pong');
});

// GET request with a param
test('GET /users/123 passes param to handler', function () {
    Router::get('/users/:id', fn (Request $request, $id) => "User $id");

    $output = simulateRequest('GET', '/users/123');

    expect($output)->toBe('User 123');
});

// Basic POST request
test('POST /echo returns posted data', function () {
    Router::post('/echo', function (Request $request) {
        return $request->body();
    });

    $output = simulateRequest('POST', '/echo', ['foo' => 'bar']);

    expect($output)->toBe(json_encode(['foo' => 'bar']));
});

// Basic PUT request
test('PUT /update returns updated data', function () {
    Router::put('/update', function (Request $request) {
        return $request->body();
    });

    $output = simulateRequest('PUT', '/update', ['id' => 1, 'name' => 'Cardikit']);

    expect($output)->toBe(json_encode(['id' => 1, 'name' => 'Cardikit']));
});

// Basic DELETE request
test('DELETE /resource/42 returns deleted confirmation', function () {
    Router::delete('/resource/:id', function (Request $request, $id) {
        return ['deleted' => (int) $id];
    });

    $output = simulateRequest('DELETE', '/resource/42');

    expect($output)->toBe(json_encode(['deleted' => 42]));
});

// If route not found, return 404
test('GET /missing returns 404 response', function () {
    $output = simulateRequest('GET', '/missing');

    expect(http_response_code())->toBe(404);
    expect($output)->toBe(json_encode(['error' => 'Route not found']));
});

// Multiple params
test('GET /posts/:postId/comments/:commentId parses multiple params', function () {
    Router::get('/posts/:postId/comments/:commentId', fn (Request $request, $postId, $commentId) => "$postId-$commentId");

    $output = simulateRequest('GET', '/posts/5/comments/8');

    expect($output)->toBe('5-8');
});

// If method not allowed, return 404
test('POST to a GET-only route returns 404', function () {
    Router::get('/only-get', fn () => 'should not be hit');

    $output = simulateRequest('POST', '/only-get');

    expect(http_response_code())->toBe(404);
    expect($output)->toBe(json_encode(['error' => 'Route not found']));
});

// If no return value, output nothing
test('GET route with no return outputs nothing', function () {
    Router::get('/empty', fn () => null);

    $output = simulateRequest('GET', '/empty');

    expect($output)->toBe('');
});
