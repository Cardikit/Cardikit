<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Models\User;
use App\Services\AuthService;

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
    /**
    * Takes a request, validates user input.
    * If input is valid, creates a new user.
    *
    * @param Request $request
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function register(Request $request): void
    {
        $data = $request->body();

        $userModel = new User();

        // validate user input
        $validator = new Validator([User::class => $userModel]);
        $valid = $validator->validateOrRespond($data, [
            'name' => 'required|min:2|max:50|type:string',
            'email' => 'required|email|unique:App\Models\User:email',
            'password' => 'required|min:8|type:string'
        ]);

        // return error if input is invalid
        if (!$valid) return;

        // create user
        $userModel->create($data);

        // set session variable
        $user = $userModel->findByEmail($data['email']);
        (new AuthService())->signIn($user);

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
    * @since 0.0.2
    */
    public function login(Request $request): void
    {
        $data = $request->body();

        // validate user input
        $validator = new Validator();
        $valid = $validator->validateOrRespond($data, [
            'email' => 'required|email',
            'password' => 'required|type:string'
        ]);

        // return error if input is invalid
        if (!$valid) return;

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
        (new AuthService())->signIn($user);

        // return success message
        Response::json(['message' => 'Logged in successfully']);
    }

    /**
    * Logs out the user.
    * Destroys session and cookie.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function logout(): void
    {
        (new AuthService())->signOut();

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
    * @since 0.0.2
    */
    public function csrfToken(): void
    {
        // generate token
        $token = (new AuthService())->issueCsrfToken();

        // return token
        Response::json(['csrf_token' => $token]);
    }
}
