<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Models\User;

/**
* Contains methods to handle user authentication.
* This includes registration, login, and logout.
*
* @package App\Controllers
*
* @since 0.0.1
*/
class AuthController
{
    protected function signIn(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $this->issueCsrfToken();
    }

    protected function issueCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token']) || strlen((string) $_SESSION['csrf_token']) !== 64) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
    * Takes a request, validates user input.
    * If input is valid, creates a new user.
    *
    * @param Request $request
    *
    * @return void
    *
    * @since 0.0.1
    */
    public function register(Request $request): void
    {
        $data = $request->body();

        $userModel = new User();

        // validate user input
        $validator = new Validator([User::class => $userModel]);
        $valid = $validator->validate($data, [
            'name' => 'required|min:2|max:50|type:string',
            'email' => 'required|email|unique:App\Models\User:email',
            'password' => 'required|min:8|type:string'
        ]);

        // return error if input is invalid
        if (!$valid) {
            Response::json([
                'errors' => $validator->errors()
            ], 422);
            return;
        }

        // create user
        $userModel->create($data);

        // set session variable
        $user = $userModel->findByEmail($data['email']);
        $this->signIn($user);

        // return success message
        Response::json([
            'message' => 'User registered successfully'
        ], 201);
    }

    /**
    * Takes a request, validates user input.
    * If input is valid, logs in the user.
    *
    * @param Request $request
    *
    * @return void
    *
    * @since 0.0.1
    */
    public function login(Request $request): void
    {
        $data = $request->body();

        // validate user input
        $validator = new Validator();
        $valid = $validator->validate($data, [
            'email' => 'required|email',
            'password' => 'required|type:string'
        ]);

        // return error if input is invalid
        if (!$valid) {
            Response::json([
                'errors' => $validator->errors()
            ], 422);
            return;
        }

        // check if user exists
        $user = User::findByEmail($data['email']);

        // return error if user does not exist
        if (!$user || !password_verify($data['password'], $user['password'])) {
            Response::json([
                'error' => 'Invalid credentials'
            ], 401);
            return;
        }

        // set session variable
        $this->signIn($user);

        // return success message
        Response::json(['message' => 'Logged in successfully']);
    }

    /**
    * Logs out the user.
    * Destroys session and cookie.
    *
    * @return void
    *
    * @since 0.0.1
    */
    public function logout(): void
    {
        // destroy session
        session_unset();
        session_destroy();

        // destroy cookie using current parameters
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 3600,
            'path' => $params['path'] ?? '/',
            'domain' => $params['domain'] ?? '',
            'secure' => $params['secure'] ?? true,
            'httponly' => $params['httponly'] ?? true,
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);

        // return success message
        Response::json(['message' => 'Logged out successfully']);
    }

    /**
    * Generates a CSRF token.
    * Sets the token in the session.
    * Returns the token as a JSON response.
    *
    * @return void
    *
    * @since 0.0.1
    */
    public function csrfToken(): void
    {
        $token = $this->issueCsrfToken();

        Response::json(['csrf_token' => $token]);
    }
}
