<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;

class PingController extends Controller
{
    public function show(Request $request, string $id): void
    {
        Response::json([
            'id' => $id,
            'message' => 'pong'
        ]);
    }

    public function create(Request $request): void
    {
        $data = $request->body();

        Response::json([
            'status' => 'User created',
            'data' => $data
        ], 201);
    }
}
