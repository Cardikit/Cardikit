<?php

use App\Core\Request;
use App\Middleware\RateLimitMiddleware;

class StubRequest extends Request
{
    public function __construct(
        protected string $method,
        protected string $uri,
        protected ?string $ip
    ) {
        // do not call parent constructor; stub methods directly
    }

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function ip(): ?string
    {
        return $this->ip;
    }
}

test('rate limit middleware allows under limit and blocks over limit', function () {
    $storePath = tempnam(sys_get_temp_dir(), 'rate_limit_');
    $middleware = new RateLimitMiddleware(1, 60, $storePath);
    $request = new StubRequest('GET', '/limited', '127.0.0.1');

    ob_start();
    $first = $middleware->handle($request);
    $firstOutput = ob_get_clean();

    ob_start();
    $second = $middleware->handle($request);
    $secondOutput = ob_get_clean();

    expect($first)->toBeTrue();
    expect($firstOutput)->toBe('');
    expect($second)->toBeFalse();
    expect($secondOutput)->toContain('Too many requests');
    expect(http_response_code())->toBe(429);

    @unlink($storePath);
});
