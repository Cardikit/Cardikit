<?php

namespace App\Routing;

use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\EnforceTlsMiddleware;
use App\Middleware\RateLimitMiddleware;
use App\Middleware\AdminMiddleware;

/**
* Groupings of middleware for
* ease of implementation.
*
* @package App\Routing
*
* @since 0.0.2
*/
class MiddlewareGroups
{
    /**
    * TLS middleware group
    *
    * @return array
    *
    * @since 0.0.2
    */
    public static function tls(): array
    {
        return [new EnforceTlsMiddleware()];
    }

    /**
    * Auth middleware group.
    * Includes TLS and Auth middlewares.
    *
    * @return array
    *
    * @since 0.0.2
    */
    public static function auth(): array
    {
        return array_merge(self::tls(), [new AuthMiddleware()]);
    }

    /**
    * Admin middleware group.
    * Includes TLS, Auth and Admin middlewares.
    *
    * @return array
    *
    * @since 0.0.3
    */
    public static function admin(): array
    {
        return array_merge(self::auth(), [new AdminMiddleware()]);
    }

    /**
    * CSRF protected middleware group.
    * Includes TLS, Auth and CSRF middlewares.
    *
    * @return array
    *
    * @since 0.0.2
    */
    public static function csrfProtected(): array
    {
        return array_merge(self::auth(), [new CsrfMiddleware()]);
    }

    /**
    * Rate limit middleware group.
    * Includes TLS and Rate Limit middlewares.
    *
    * @return array
    *
    * @since 0.0.2
    */
    public static function rateLimited(int $maxAttempts, int $decaySeconds): array
    {
        return array_merge(self::tls(), [new RateLimitMiddleware($maxAttempts, $decaySeconds)]);
    }

    /**
    * Mutating middleware group.
    * Includes CSRF and Rate Limit middlewares.
    *
    * @return array
    *
    * @since 0.0.2
    */
    public static function mutating(int $rate = 60, int $windowSeconds = 60): array
    {
        return array_merge(self::csrfProtected(), [new RateLimitMiddleware($rate, $windowSeconds)]);
    }
}
