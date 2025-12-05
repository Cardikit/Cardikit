<?php

use App\Core\Request;
use App\Core\Router;
use App\Middleware\MiddlewareInterface;

beforeEach(function () {
    $ref = new ReflectionClass(Router::class);
    foreach (['routes', 'middleware'] as $prop) {
        $property = $ref->getProperty($prop);
        $property->setAccessible(true);
        $property->setValue(null, []);
    }
});

class TestMiddleware implements MiddlewareInterface
{
    public bool $called = false;
    public bool $allow;

    public function __construct(bool $allow = true)
    {
        $this->allow = $allow;
    }

    public function handle(Request $request): bool
    {
        $this->called = true;
        return $this->allow;
    }
}

test('router dispatches route with params and middleware pipeline', function () {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/items/42';

    $middleware = new TestMiddleware(true);
    Router::get('/items/:id', function (Request $req, $id) {
        return "item-$id";
    }, [$middleware]);

    ob_start();
    Router::dispatch();
    $output = trim((string) ob_get_clean());

    expect($middleware->called)->toBeTrue();
    expect($output)->toBe('item-42');
});

test('router stops when middleware blocks', function () {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/secure';

    $middleware = new TestMiddleware(false);
    Router::get('/secure', function () {
        throw new RuntimeException('Should not reach handler');
    }, [$middleware]);

    ob_start();
    Router::dispatch();
    $output = (string) ob_get_clean();

    expect($middleware->called)->toBeTrue();
    // Default Response::json from middleware will be empty if custom; just assert nothing from handler
    expect($output)->not->toContain('Should not reach handler');
});
