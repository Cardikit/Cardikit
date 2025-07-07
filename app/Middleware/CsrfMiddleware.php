<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class CsrfMiddleware
{
    public function handle(Request $request): bool
    {
        $sessionToken = $_SESSION['csrf_token'] ?? null;
        $headers = $request->getHeaders();
        $headerToken = $headers['X-CSRF-TOKEN'] ?? null;

        if (!$sessionToken || !$headerToken || !hash_equals($sessionToken, $headerToken)) {
            Response::json(['error' => 'Invalid CSRF token'], 403);
            return false;
        }

        return true;
    }
}
