<?php

use App\Core\Request;
use App\Middleware\RateLimitMiddleware;

beforeEach(function () {
    $_SESSION = [];
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/test';

    $this->storePath = sys_get_temp_dir() . '/cardikit_rate_limit_test.json';
    if (file_exists($this->storePath)) {
        unlink($this->storePath);
    }
});

test('allows requests under rate limit', function () {
    $middleware = new RateLimitMiddleware(3, 60, $this->storePath);

    $request = new Request();
    expect($middleware->handle($request))->toBeTrue();
    expect($middleware->handle($request))->toBeTrue();
    expect($middleware->handle($request))->toBeTrue();
});

test('blocks request when rate limit exceeded', function () {
    $middleware = new RateLimitMiddleware(2, 60, $this->storePath);
    $request = new Request();

    $middleware->handle($request);
    $middleware->handle($request);
    ob_start();
    $result = $middleware->handle($request);
    $output = ob_get_clean();

    expect($result)->toBeFalse();
    expect($output)->toContain('Too many requests');
});
