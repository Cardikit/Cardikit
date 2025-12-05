<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

/**
* Allows acces to the route if the
* user is authenticated
*
* @package App\Middleware
*
* @since 0.0.1
*/
class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request): bool
    {
        if (!isset($_SESSION['user_id'])) {
            Response::json(['error' => 'Unauthorized'], 401);
            return false;
        }

        return true;
    }
}
