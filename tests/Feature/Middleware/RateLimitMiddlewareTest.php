<?php

use App\Core\Request;
use App\Middleware\RateLimitMiddleware;

beforeEach(function () {
    $_SESSION = [];
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
});

test('allows requests under rate limit', function () {
    $middleware = new RateLimitMiddleware(3, 60);

    $request = new Request();
    expect($middleware->handle($request))->toBeTrue();
    expect($middleware->handle($request))->toBeTrue();
    expect($middleware->handle($request))->toBeTrue();
});

test('blocks request when rate limit exceeded', function () {
    $middleware = new RateLimitMiddleware(2, 60);
    $request = new Request();

    $middleware->handle($request);
    $middleware->handle($request);
    ob_start();
    $result = $middleware->handle($request);
    $output = ob_get_clean();

    expect($result)->toBeFalse();
    expect($output)->toContain('Too many requests');
});
