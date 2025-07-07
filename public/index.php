<?php

use App\Core\Router;
use App\Controllers\PingController;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RateLimitMiddleware;

require __DIR__ . '/../bootstrap.php';

session_start();

Router::get('/ping/:id', [PingController::class, 'show']);
Router::get('/db', [PingController::class, 'db']);

Router::post('/register', [AuthController::class, 'register'], [new RateLimitMiddleware(5, 60)]);

Router::post('/login', [AuthController::class, 'login'], [new RateLimitMiddleware(5, 60)]);

Router::post('/logout', [AuthController::class, 'logout']);

Router::get('/@me', [UserController::class, 'me'], [AuthMiddleware::class]);

Router::dispatch();
