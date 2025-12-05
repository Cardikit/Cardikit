<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

/**
* Middleware class for CSRF (Cross-Site Request Forgery) protection.
* Checks if the CSRF token in the session matches the token in the request headers.
*
* @package App\Middleware
*
* @since 0.0.1
*/
class CsrfMiddleware implements MiddlewareInterface
{
    public function handle(Request $request): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $sessionToken = $_SESSION['csrf_token'] ?? null;

        $headers = array_change_key_case($request->getHeaders(), CASE_LOWER);
        $headerToken = $headers['x-csrf-token'] ?? $headers['x-xsrf-token'] ?? null;
        $body = $request->body();
        $bodyToken = $body['csrf_token'] ?? null;
        $candidateToken = $headerToken ?? $bodyToken;

        if (!$sessionToken || !$candidateToken || !is_string($candidateToken) || strlen($candidateToken) !== 64) {
            Response::json(['error' => 'Invalid CSRF token'], 403);
            return false;
        }

        if (!hash_equals($sessionToken, $candidateToken)) {
            Response::json(['error' => 'Invalid CSRF token'], 403);
            return false;
        }

        return true;
    }
}
