<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Models\User;
use App\Services\AuthService;

/**
* Allows access to the route if the
* user is an admin
*
* @package App\Middleware
*
* @since 0.0.1
*/
class AdminMiddleware implements MiddlewareInterface
{
    public function handle(Request $request): bool
    {
        if (!isset($_SESSION['user_id'])) {
            Response::json(['error' => 'Unauthorized'], 401);
            return false;
        }

        $userId = (new AuthService())->currentUserId();
        $user = $userId ? User::findById($userId) : null;

        if (!$user) {
            Response::json(['error' => 'Unauthorized'], 401);
            return false;
        }

        $role = isset($user['role']) ? (int) $user['role'] : 0;

        if ($role < 2) {
            Response::json(['error' => 'Unauthorized'], 401);
            return false;
        }

        return true;
    }
}
