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
}
