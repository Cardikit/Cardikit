<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Response;
use App\Core\Request;
use App\Core\Validator;
use App\Services\AuthService;

/**
* Contains methods for user profile management.
*
* @package App\Controllers
*
* @since 0.0.2
*/
class UserController
{
    /**
    * Get the authenticated user's profile.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function me(): void
    {
        // Get the currently logged in user
        $user = User::findLoggedInUser();

        // Return the user
        Response::json($user);
    }

    /**
     * Update the authenticated user's profile (name/email/password).
     * Requires the current password for any change. New passwords must be confirmed.
     *
     * @param Request $request user data to change
     *
     * @return void
     *
     * @since 0.0.2
     */
    public function update(Request $request): void
    {
        // Get the currently logged in user
        $userId = (new AuthService())->currentUserId();
        $user = $userId ? User::findById((int) $userId) : null;

        // Fail user not found
        if (!$user) {
            Response::json(['message' => 'Unauthorized'], 401);
            return;
        }

        // Get request data
        $data = $request->body();

        $rules = [
            'current_password' => 'required|type:string',
        ];

        $payload = [];

        // Set rules and payload
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

        // validate user input
        $validator = new Validator([User::class => new User()]);
        if (!$validator->validateOrRespond($data, $rules)) return;

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

        // Update the user
        $updated = (new User())->updateById($user['id'], $finalPayload);

        if (!$updated) {
            Response::json([
                'message' => 'Failed to update account'
            ], 500);
            return;
        }

        // Return the updated user
        Response::json([
            'message' => 'Account updated successfully',
            'user' => User::findLoggedInUser()
        ], 200);
    }

    /**
     * Delete the authenticated user's account after confirming password.
     *
     * @param Request $request password confirmation
     *
     * @return void
     *
     * @since 0.0.2
     */
    public function delete(Request $request): void
    {
        // Get the currently logged in user
        $userId = (new AuthService())->currentUserId();
        $user = $userId ? User::findById((int) $userId) : null;

        if (!$user) {
            Response::json(['message' => 'Unauthorized'], 401);
            return;
        }

        // Get request data
        $data = $request->body();

        // validate user password
        $validator = new Validator();
        $valid = $validator->validateOrRespond($data, [
            'password' => 'required|type:string',
        ]);

        if (!$valid) return;

        if (!password_verify((string) $data['password'], $user['password'])) {
            Response::json(['message' => 'Invalid password'], 401);
            return;
        }

        // Delete user and cascade manually for related records as needed.
        $dbUser = new User();
        $deleted = $dbUser->deleteById((int) $user['id']);

        if (!$deleted) {
            Response::json(['message' => 'Failed to delete account'], 500);
            return;
        }

        // logout the user
        session_unset();
        session_destroy();

        Response::json(['message' => 'Account deleted']);
    }
}
