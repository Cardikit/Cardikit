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
use App\Controllers\BlogController;
use App\Controllers\CategoryController;
use App\Controllers\SitemapController;
use App\Controllers\BlogImageController;

// Middleware groups
$tls = MiddlewareGroups::tls();
$auth = MiddlewareGroups::auth();
$admin = MiddlewareGroups::admin();
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

// Blog
Router::get('/blog', [BlogController::class, 'index'], $tls);
Router::get('/blog/create', [BlogController::class, 'create'], $admin);
Router::get('/blog/admin', [BlogController::class, 'adminIndex'], $admin);
Router::get('/blog/:id/edit', [BlogController::class, 'edit'], $admin);
Router::post('/blog', [BlogController::class, 'store'], $admin);
Router::put('/blog/:id', [BlogController::class, 'update'], $admin);
Router::delete('/blog/:id', [BlogController::class, 'delete'], $admin);
Router::get('/blog/images', [BlogImageController::class, 'index'], $admin);
Router::get('/blog/images/upload', [BlogImageController::class, 'create'], $admin);
Router::post('/blog/images', [BlogImageController::class, 'store'], $admin);
Router::delete('/blog/images/:filename', [BlogImageController::class, 'delete'], $admin);
Router::get('/sitemap.xml', [SitemapController::class, 'index'], $tls);
Router::get('/privacy', fn () => \App\Core\View::render('privacy'), $tls);
Router::get('/terms', fn () => \App\Core\View::render('terms'), $tls);

// Category
Router::get('/blog/categories', [CategoryController::class, 'index'], $tls);
Router::get('/blog/categories/admin', [CategoryController::class, 'adminIndex'], $admin);
Router::get('/blog/categories/:id/edit', [CategoryController::class, 'edit'], $admin);
Router::get('/blog/:slug', [CategoryController::class, 'show'], $tls);
Router::get('/blog/categories/create', [CategoryController::class, 'create'], $admin);
Router::post('/blog/categories', [CategoryController::class, 'store'], $admin);
Router::put('/blog/categories/:id', [CategoryController::class, 'update'], $admin);
Router::delete('/blog/categories/:id', [CategoryController::class, 'delete'], $admin);

Router::get('/blog/:category/:slug', [BlogController::class, 'show'], $tls);
