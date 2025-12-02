<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Response;
use App\Core\Request;
use App\Core\Validator;

class UserController
{
    public function me()
    {
        $user = User::findLoggedInUser();

        Response::json($user);
    }

    /**
     * Update the authenticated user's profile (name/email/password).
     * Requires the current password for any change. New passwords must be confirmed.
     */
    public function update(Request $request): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        $user = $userId ? User::findById((int) $userId) : null;

        if (!$user) {
            Response::json(['message' => 'Unauthorized'], 401);
            return;
        }

        $data = $request->body();

        $rules = [
            'current_password' => 'required|type:string',
        ];

        $payload = [];

        if (array_key_exists('name', $data)) {
            $rules['name'] = 'required|min:2|max:50|type:string';
            $payload['name'] = trim((string) $data['name']);
        }

        if (array_key_exists('email', $data)) {
            $rules['email'] = 'required|email|type:string';
            $payload['email'] = trim((string) $data['email']);
        }

        if (!empty($data['password'])) {
            $rules['password'] = 'required|min:8|confirmed|type:string';
            $payload['password'] = (string) $data['password'];
        }

        if (empty($payload)) {
            Response::json([
                'message' => 'No changes provided',
            ], 422);
            return;
        }

        $validator = new Validator([User::class => new User()]);
        $valid = $validator->validate($data, $rules);

        if (!$valid) {
            Response::json([
                'errors' => $validator->errors()
            ], 422);
            return;
        }

        if (!password_verify((string) $data['current_password'], $user['password'])) {
            Response::json([
                'message' => 'Invalid current password'
            ], 401);
            return;
        }

        if (isset($payload['email']) && strtolower($payload['email']) !== strtolower($user['email'])) {
            $existing = User::findByEmail($payload['email']);
            if ($existing && $existing['id'] !== $user['id']) {
                Response::json([
                    'errors' => ['email' => ['Email is already taken']]
                ], 422);
                return;
            }
        }

        // If nothing actually changed, return early.
        $finalPayload = [];
        if (isset($payload['name']) && $payload['name'] !== $user['name']) {
            $finalPayload['name'] = $payload['name'];
        }
        if (isset($payload['email']) && strtolower($payload['email']) !== strtolower($user['email'])) {
            $finalPayload['email'] = $payload['email'];
        }
        if (isset($payload['password'])) {
            $finalPayload['password'] = $payload['password'];
        }

        if (empty($finalPayload)) {
            Response::json([
                'message' => 'No changes detected'
            ], 200);
            return;
        }

        $updated = (new User())->updateById($user['id'], $finalPayload);

        if (!$updated) {
            Response::json([
                'message' => 'Failed to update account'
            ], 500);
            return;
        }

        Response::json([
            'message' => 'Account updated successfully',
            'user' => User::findLoggedInUser()
        ], 200);
    }
}
