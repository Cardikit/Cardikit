<?php

use App\Core\Request;
use App\Middleware\CsrfMiddleware;

class CsrfStubRequest extends Request
{
    protected array $headers;
    protected array $body;

    public function __construct(array $headers = [], array $body = [])
    {
        // prevent parent constructor from reading globals
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function body(): array
    {
        return $this->body;
    }
}

test('csrf middleware allows valid token in header', function () {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['csrf_token'] = str_repeat('a', 64);

    $middleware = new CsrfMiddleware();
    $request = new CsrfStubRequest(['X-CSRF-TOKEN' => $_SESSION['csrf_token']]);

    ob_start();
    $result = $middleware->handle($request);
    ob_end_clean();

    expect($result)->toBeTrue();
});

test('csrf middleware rejects missing or mismatched token', function () {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['csrf_token'] = str_repeat('b', 64);

    $middleware = new CsrfMiddleware();
    $request = new CsrfStubRequest([], []); // no token provided

    ob_start();
    $result = $middleware->handle($request);
    $output = ob_get_clean();

    expect($result)->toBeFalse();
    expect($output)->toContain('Invalid CSRF token');
    expect(http_response_code())->toBe(403);
});
