<?php

use App\Middleware\AuthMiddleware;
use App\Core\Request;

beforeEach(function () {
    $_SESSION = [];
});

it('blocks guests', function () {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/';

    $middleware = new AuthMiddleware();
    $request = new Request();

    ob_start();
    $result = $middleware->handle($request);
    $output = ob_get_clean();

    expect($result)->toBeFalse();
    expect($output)->toContain('Unauthorized');
});

it('allows authenticated users', function () {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/';
    $_SESSION['user_id'] = 1;

    $middleware = new AuthMiddleware();
    $request = new Request();

    ob_start();
    $result = $middleware->handle($request);
    $output = ob_get_clean();

    expect($result)->toBeTrue();
    expect($output)->toBeEmpty();
});
