<?php

use App\Core\Router;
use App\Controllers\PingController;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;

require __DIR__ . '/../bootstrap.php';

session_start();

Router::get('/ping/:id', [PingController::class, 'show']);
Router::get('/db', [PingController::class, 'db']);
Router::post('/register', [AuthController::class, 'register']);
Router::post('/login', [AuthController::class, 'login']);
Router::post('/logout', [AuthController::class, 'logout']);

Router::get('/@me', [UserController::class, 'me'], [AuthMiddleware::class]);

Router::dispatch();
