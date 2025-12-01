<?php

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\CardController;
use App\Controllers\PublicCardController;
use App\Controllers\LandingController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\EnforceTlsMiddleware;
use App\Middleware\RateLimitMiddleware;
use App\Core\Config;

require __DIR__ . '/../bootstrap.php';

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['SERVER_PORT'] ?? null) == 443)
    || (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => Config::get('SESSION_DOMAIN', ''),
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);

$tls = [new EnforceTlsMiddleware()];
$auth = array_merge($tls, [new AuthMiddleware()]);
$mutating = array_merge($auth, [new CsrfMiddleware(), new RateLimitMiddleware(60, 60)]);

session_name('cardikit_session');
session_start();

Router::get('/landing', [LandingController::class, 'show'], $tls);
Router::get('/c/:id', [PublicCardController::class, 'show'], $tls);

Router::post('/api/v1/register', [AuthController::class, 'register'], array_merge($tls, [new RateLimitMiddleware(5, 60)]));

Router::post('/api/v1/login', [AuthController::class, 'login'], array_merge($tls, [new RateLimitMiddleware(5, 60)]));

Router::post('/api/v1/logout', [AuthController::class, 'logout'], array_merge($auth, [new CsrfMiddleware(), new RateLimitMiddleware(20, 60)]));

Router::get('/api/v1/@me', [UserController::class, 'me'], array_merge($auth, [new RateLimitMiddleware(60, 60)]));

Router::get('/api/v1/@me/cards', [CardController::class, 'index'], array_merge($auth, [new RateLimitMiddleware(60, 60)]));

Router::get('/api/v1/@me/cards/:id', [CardController::class, 'show'], array_merge($auth, [new RateLimitMiddleware(60, 60)]));

Router::post('/api/v1/@me/cards', [CardController::class, 'create'], $mutating);

Router::put('/api/v1/@me/cards/:id', [CardController::class, 'update'], $mutating);

Router::delete('/api/v1/@me/cards/:id', [CardController::class, 'delete'], $mutating);

Router::post('/api/v1/@me/cards/:id/qr', [CardController::class, 'generateQr'], $mutating);

Router::get('/api/v1/csrf-token', [AuthController::class, 'csrfToken'], array_merge($auth, [new RateLimitMiddleware(30, 60)]));

Router::get('/api/v1/themes', [CardController::class, 'themes'], array_merge($auth, [new RateLimitMiddleware(60, 60)]));

Router::dispatch();
