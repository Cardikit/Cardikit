<?php

use App\Core\Request;
use App\Middleware\CsrfMiddleware;

test('CSRF middleware fails with invalid token', function () {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SERVER['HTTP_X_CSRF_TOKEN'] = 'invalidtoken';

    $request = new Request();
    $middleware = new CSRFMiddleware();

    ob_start();
    $result = $middleware->handle($request);
    ob_end_clean();

    expect($result)->toBeFalse();
});

test ('CSRF middleware fails when token is missing', function () {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    unset($_SERVER['HTTP_X_CSRF_TOKEN']);

    $request = new Request();
    $middleware = new CsrfMiddleware();

    ob_start();
    $result = $middleware->handle($request);
    ob_end_clean();

    expect($result)->toBeFalse();
});

test ('CSRF middleware passes when token is valid', function () {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SERVER['HTTP_X_CSRF_TOKEN'] = $_SESSION['csrf_token'];

    $request = new Request();
    $middleware = new CsrfMiddleware();

    ob_start();
    $result = $middleware->handle($request);
    ob_end_clean();

    expect($result)->toBeTrue();
});
