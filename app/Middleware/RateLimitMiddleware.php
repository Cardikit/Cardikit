<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

/**
* Rate limits users based on the provided parameters.
* Takes max attempts and decay seconds.
*
* @package App\Middleware
*
* @since 0.0.1
*/
class RateLimitMiddleware
{
    protected int $maxAttempts;
    protected int $decaySeconds;

    public function __construct(int $maxAttempts = 5, int $decaySeconds = 60)
    {
        $this->maxAttempts = $maxAttempts;
        $this->decaySeconds = $decaySeconds;
    }

    public function handle(Request $request): bool
    {
        $key = $this->resolveKey($request);
        $currentTime = time();

        if (!isset($_SESSION['rate_limit'][$key])) {
            $_SESSION['rate_limit'][$key] = [
                'hits' => 0,
                'reset_time' => $currentTime + $this->decaySeconds,
            ];
        }

        $bucket = &$_SESSION['rate_limit'][$key];

        if ($currentTime > $bucket['reset_time']) {
            $bucket['hits'] = 0;
            $bucket['reset_time'] = $currentTime + $this->decaySeconds;
        }

        if ($bucket['hits'] >= $this->maxAttempts) {
            Response::json([
                'error' => 'Too many requests. Please try again later.'
            ], 429);
            return false;
        }

        $bucket['hits']++;
        return true;
    }

    protected function resolveKey(Request $request): string
    {
        $method = $request->method();
        $uri = $request->uri();
        return 'ip:' . ($request->ip() ?? 'unknown') . ':' . $method . ':' . $uri;
    }
}
