<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Response;

class UserController
{
    public function me()
    {
        $user = User::findLoggedInUser();

        Response::json($user);
    }
}
