<?php

namespace App\Middleware;

use App\Core\Config;
use App\Core\Request;
use App\Core\Response;

/**
 * Ensures requests arrive over HTTPS before they reach application code.
 */
class EnforceTlsMiddleware
{
    public function handle(Request $request): bool
    {
        $forceHttps = filter_var(Config::get('FORCE_HTTPS', true), FILTER_VALIDATE_BOOL);

        if (!$forceHttps) {
            return true;
        }

        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['SERVER_PORT'] ?? null) === 443)
            || (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

        if ($isHttps) {
            header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
            return true;
        }

        Response::json(['error' => 'TLS required'], 403);
        return false;
    }
}
