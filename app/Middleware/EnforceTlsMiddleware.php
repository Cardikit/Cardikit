<?php

namespace App\Middleware;

use App\Core\Config;
use App\Core\Request;
use App\Core\Response;

/**
* Ensures requests arrive over HTTPS before they reach application code.
*
* @package App\Middleware
*
* @since 0.0.2
*/
class EnforceTlsMiddleware implements MiddlewareInterface
{
    public function handle(Request $request): bool
    {
        $forceHttps = filter_var(Config::get('FORCE_HTTPS', true), FILTER_VALIDATE_BOOL);
        $trustedProxies = array_filter(array_map('trim', explode(',', Config::get('TRUSTED_PROXIES', ''))));
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';

        if (!$forceHttps) {
            return true;
        }

        $forwardedProto = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '');
        $canTrustForwarded = $forwardedProto && in_array($remoteAddr, $trustedProxies, true);

        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['SERVER_PORT'] ?? null) === 443)
            || ($canTrustForwarded && $forwardedProto === 'https');

        if ($isHttps) {
            header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
            return true;
        }

        Response::json(['error' => 'TLS required'], 403);
        return false;
    }
}
