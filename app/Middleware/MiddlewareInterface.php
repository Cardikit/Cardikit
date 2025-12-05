<?php

namespace App\Middleware;

use App\Core\Request;

/**
* Interface for middleware classes
*
* @package App\Middleware
*
* @since 0.0.2
*/
interface MiddlewareInterface
{
    /**
     * Handle the incoming request.
     *
     * Return false to halt execution; the middleware is expected to send the response itself.
     *
     * @param Request $request
     *
     * @return bool
     *
     * @since 0.0.2
     */
    public function handle(Request $request): bool;
}
