<?php

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\CardController;
use App\Controllers\PublicCardController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\RateLimitMiddleware;

require __DIR__ . '/../bootstrap.php';

session_start();

Router::post('/api/v1/register', [AuthController::class, 'register'], [new RateLimitMiddleware(5, 60)]);

Router::post('/api/v1/login', [AuthController::class, 'login'], [new RateLimitMiddleware(5, 60)]);

Router::post('/api/v1/logout', [AuthController::class, 'logout'], [new AuthMiddleware(), new CsrfMiddleware()]);

Router::get('/api/v1/@me', [UserController::class, 'me'], [new AuthMiddleware()]);

Router::get('/api/v1/@me/cards', [CardController::class, 'index'], [new AuthMiddleware()]);

Router::get('/api/v1/@me/cards/:id', [CardController::class, 'show'], [new AuthMiddleware()]);

Router::post('/api/v1/@me/cards', [CardController::class, 'create'], [new AuthMiddleware(), new CsrfMiddleware()]);

Router::put('/api/v1/@me/cards/:id', [CardController::class, 'update'], [new AuthMiddleware(), new CsrfMiddleware()]);

Router::delete('/api/v1/@me/cards/:id', [CardController::class, 'delete'], [new AuthMiddleware(), new CsrfMiddleware()]);

Router::post('/api/v1/@me/cards/:id/qr', [CardController::class, 'generateQr'], [new AuthMiddleware(), new CsrfMiddleware()]);

Router::get('/api/v1/csrf-token', [AuthController::class, 'csrfToken']);

Router::get('/api/v1/themes', [CardController::class, 'themes'], [new AuthMiddleware()]);

Router::get('/c/:id', [PublicCardController::class, 'show']);

Router::dispatch();
