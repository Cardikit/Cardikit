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
class CsrfMiddleware
{
    public function handle(Request $request): bool
    {
        $sessionToken = $_SESSION['csrf_token'] ?? null;


        $headers = array_change_key_case($request->getHeaders(), CASE_UPPER);
        $headerToken = $headers['X-CSRF-TOKEN'] ?? null;

        if (!$sessionToken || !$headerToken || !hash_equals($sessionToken, $headerToken)) {
            Response::json(['error' => 'Invalid CSRF token'], 403);
            return false;
        }

        return true;
    }
}
