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
    protected string $storePath;

    public function __construct(int $maxAttempts = 5, int $decaySeconds = 60, ?string $storePath = null)
    {
        $this->maxAttempts = $maxAttempts;
        $this->decaySeconds = $decaySeconds;
        $this->storePath = $storePath ?? sys_get_temp_dir() . '/cardikit_rate_limit.json';
    }

    public function handle(Request $request): bool
    {
        $key = $this->resolveKey($request);
        $currentTime = time();

        $buckets = $this->readStore();
        if (!isset($buckets[$key])) {
            $buckets[$key] = [
                'hits' => 0,
                'reset_time' => $currentTime + $this->decaySeconds,
            ];
        }

        $bucket = &$buckets[$key];

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
        $this->writeStore($buckets);
        return true;
    }

    protected function resolveKey(Request $request): string
    {
        $method = $request->method();
        $uri = $request->uri();
        $userKey = $_SESSION['user_id'] ?? null;
        $prefix = $userKey ? 'user:' . $userKey : 'ip:' . ($request->ip() ?? 'unknown');
        return $prefix . ':' . $method . ':' . $uri;
    }

    protected function readStore(): array
    {
        if (!file_exists($this->storePath)) {
            return [];
        }

        $handle = fopen($this->storePath, 'r');
        if (!$handle) {
            return [];
        }

        $data = [];
        if (flock($handle, LOCK_SH)) {
            $json = stream_get_contents($handle);
            $data = json_decode($json ?: '[]', true) ?: [];
            flock($handle, LOCK_UN);
        }
        fclose($handle);

        return $data;
    }

    protected function writeStore(array $data): void
    {
        $handle = fopen($this->storePath, 'c+');
        if (!$handle) {
            return;
        }

        if (flock($handle, LOCK_EX)) {
            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode($data));
            fflush($handle);
            flock($handle, LOCK_UN);
        }

        fclose($handle);
    }
}
