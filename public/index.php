<?php

use App\Core\Router;
use App\Controllers\PingController;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\RateLimitMiddleware;

require __DIR__ . '/../bootstrap.php';

session_start();

Router::get('/api/ping/:id', [PingController::class, 'show']);
Router::get('/db', [PingController::class, 'db']);

Router::post('/api/v1/register', [AuthController::class, 'register'], [new RateLimitMiddleware(5, 60)]);

Router::post('/api/v1/login', [AuthController::class, 'login'], [new RateLimitMiddleware(5, 60)]);

Router::post('/api/v1/logout', [AuthController::class, 'logout'], [new AuthMiddleware(), new CsrfMiddleware()]);

Router::get('/api/v1/@me', [UserController::class, 'me'], [new AuthMiddleware()]);

Router::get('/api/v1/csrf-token', [AuthController::class, 'csrfToken']);

Router::dispatch();
