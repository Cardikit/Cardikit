<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Models\User;

class AuthController
{
    public function register(Request $request): void
    {
        $data = $request->body();

        $validator = new Validator();
        $valid = $validator->validate($data, [
            'name' => 'required|min:2|max:10|type:string',
            'email' => 'required|email|unique:users:email',
            'password' => 'required|min:8|type:string|confirmed'
        ]);

        if (!$valid) {
            Response::json([
                'errors' => $validator->errors()
            ], 422);
            return;
        }

        User::create($data);

        Response::json([
            'message' => 'User registered successfully'
        ], 201);
    }

    public function login(Request $request): void
    {
        $data = $request->body();

        $validator = new Validator();
        $valid = $validator->validate($data, [
            'email' => 'required|email',
            'password' => 'required|type:string'
        ]);

        if (!$valid) {
            Response::json([
                'errors' => $validator->errors()
            ], 422);
            return;
        }

        $user = User::findByEmail($data['email']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            Response::json([
                'message' => 'Invalid credentials'
            ], 401);
            return;
        }

        $_SESSION['user_id'] = $user['id'];

        Response::json(['message' => 'Logged in successfully']);
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();

        setcookie(session_name(), '', time() - 3600, '/');

        Response::json(['message' => 'Logged out successfully']);
    }
}
