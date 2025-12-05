<?php

use App\Core\Request;
use App\Middleware\EnforceTlsMiddleware;

test('enforce tls allows https and blocks http when forced', function () {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

    $middleware = new EnforceTlsMiddleware();
    $request = new Request();

    ob_start();
    $allowed = $middleware->handle($request);
    ob_end_clean();

    expect($allowed)->toBeTrue();

    // Simulate http
    unset($_SERVER['HTTPS']);
    $_SERVER['SERVER_PORT'] = 80;

    ob_start();
    $blocked = $middleware->handle($request);
    $output = ob_get_clean();

    expect($blocked)->toBeFalse();
    expect($output)->toContain('TLS required');
    expect(http_response_code())->toBe(403);
});
