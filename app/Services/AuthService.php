<?php

namespace App\Services;

use App\Models\User;

/**
* Contains methods for authenticating users.
*
* @package App\Services
*
* @since 0.0.2
*/
class AuthService
{
    /**
    * Signs in a user.
    *
    * @param array $user
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function signIn(array $user): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $this->issueCsrfToken();
    }

    /**
    * Signs out a user.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function signOut(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        session_unset();
        session_destroy();

        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 3600,
            'path' => $params['path'] ?? '/',
            'domain' => $params['domain'] ?? '',
            'secure' => $params['secure'] ?? true,
            'httponly' => $params['httponly'] ?? true,
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
    }

    /**
    * Issues a CSRF token.
    *
    * @return string
    *
    * @since 0.0.2
    */
    public function issueCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token']) || strlen((string) $_SESSION['csrf_token']) !== 64) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
    * Returns the current user's ID.
    *
    * @return int|null
    *
    * @since 0.0.2
    */
    public function currentUserId(): ?int
    {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }

    /**
    * Returns the current user.
    *
    * @return array|null
    *
    * @since 0.0.2
    */
    public function currentUser(): ?array
    {
        $id = $this->currentUserId();
        if ($id === null) {
            return null;
        }

        return User::findById($id);
    }
}
