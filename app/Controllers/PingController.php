<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Core\Config;

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

    public function db(): void
    {
        try {
            $pdo = Database::connect();
            $stmt = $pdo->query('SELECT 1');
            $result = $stmt->fetchColumn();

            Response::json([
                'status' => 'connected',
                'result' => $result
            ]);
        } catch (\Throwable $e) {
            Response::json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
