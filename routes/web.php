<?php

/**
* Routing for http requests.
* Based on the URL, the specified controller and method
* will be called.
* Middleware groups can be specified for each route.
*
* @since 0.0.2
*/

use App\Controllers\AuthController;
use App\Controllers\CardController;
use App\Controllers\LandingController;
use App\Controllers\PublicCardController;
use App\Controllers\SpaController;
use App\Controllers\UserController;
use App\Core\Router;
use App\Routing\MiddlewareGroups;
use App\Middleware\RateLimitMiddleware;

// Middleware groups
$tls = MiddlewareGroups::tls();
$auth = MiddlewareGroups::auth();
$mutating = MiddlewareGroups::mutating(60, 60);

// Public
Router::get('/', [LandingController::class, 'show'], $tls);
Router::get('/c/:slug', [PublicCardController::class, 'show'], $tls);

// Card editor
Router::get('/app', [SpaController::class, 'show'], $tls);
Router::get('/app/:path', [SpaController::class, 'show'], $tls);
Router::get('/app/:path/:subpath', [SpaController::class, 'show'], $tls);
Router::get('/app/:path/:subpath/:child', [SpaController::class, 'show'], $tls);

// Auth
Router::post('/api/v1/register', [AuthController::class, 'register'], MiddlewareGroups::rateLimited(5, 60));
Router::post('/api/v1/login', [AuthController::class, 'login'], MiddlewareGroups::rateLimited(5, 60));
Router::post('/api/v1/logout', [AuthController::class, 'logout'], array_merge($auth, [new RateLimitMiddleware(20, 60)]));
Router::get('/api/v1/csrf-token', [AuthController::class, 'csrfToken'], array_merge($auth, [new RateLimitMiddleware(30, 60)]));

// User
Router::get('/api/v1/@me', [UserController::class, 'me'], array_merge($auth, [new RateLimitMiddleware(60, 60)]));
Router::put('/api/v1/@me', [UserController::class, 'update'], $mutating);
Router::delete('/api/v1/@me', [UserController::class, 'delete'], $mutating);

// Cards
Router::get('/api/v1/@me/cards', [CardController::class, 'index'], array_merge($auth, [new RateLimitMiddleware(60, 60)]));
Router::get('/api/v1/@me/cards/:id', [CardController::class, 'show'], array_merge($auth, [new RateLimitMiddleware(60, 60)]));
Router::post('/api/v1/@me/cards', [CardController::class, 'create'], $mutating);
Router::put('/api/v1/@me/cards/:id', [CardController::class, 'update'], $mutating);
Router::delete('/api/v1/@me/cards/:id', [CardController::class, 'delete'], $mutating);
Router::post('/api/v1/@me/cards/:id/qr', [CardController::class, 'generateQr'], $mutating);

// Themes
Router::get('/api/v1/themes', [CardController::class, 'themes'], array_merge($auth, [new RateLimitMiddleware(60, 60)]));
