<?php

namespace App\Middleware;

use App\Core\Request;

/**
* Adds common security headers to responses.
*
* @since 0.0.4
*/
class SecurityHeadersMiddleware implements MiddlewareInterface
{
    public function handle(Request $request): bool
    {
        $this->setHeaderOnce('X-Content-Type-Options', 'nosniff');
        $this->setHeaderOnce('X-Frame-Options', 'SAMEORIGIN');
        $this->setHeaderOnce('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->setHeaderOnce('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // CSP allows self assets and GA
        $csp = implode('; ', [
            "default-src 'self'",
            "img-src 'self' data: https://cardikit.com https://www.googletagmanager.com https://www.google-analytics.com",
            "script-src 'self' 'unsafe-inline' https://www.googletagmanager.com https://www.google-analytics.com",
            "style-src 'self' 'unsafe-inline'",
            "connect-src 'self' https://www.google-analytics.com",
            "font-src 'self' data:",
            "object-src 'none'",
            "frame-ancestors 'self'",
            "base-uri 'self'",
        ]);
        $this->setHeaderOnce('Content-Security-Policy', $csp);

        // Caching: long cache for assets, no-store for dynamic/API.
        if (!headers_sent()) {
            $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
            $isAsset = preg_match('/\\.(css|js|png|jpg|jpeg|webp|svg|gif)$/i', $uri ?? '');
            $isApi = str_starts_with($uri ?? '', '/api/');

            if ($isAsset) {
                header('Cache-Control: public, max-age=31536000, immutable');
            } else {
                header('Cache-Control: no-store, must-revalidate');
                header('Pragma: no-cache');
            }
        }

        return true;
    }

    protected function setHeaderOnce(string $name, string $value): void
    {
        if (!headers_sent() && empty($_SERVER['HTTP_' . strtoupper(str_replace('-', '_', $name))])) {
            header($name . ': ' . $value);
        }
    }
}
