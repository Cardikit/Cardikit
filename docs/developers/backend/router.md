---
layout: home
title: Router
nav_order: 1
parent: Backend
grandparent: Developers
---

# 🧭 Router

Cardikit's routing system maps incoming HTTP requests to their approprate controllers, methods, and middleware. Routes are grouped by HTTP method and support both static and dynamic URI segments.

> 📍 Route definitions live in `routes/web.php`, which is required from `public/index.php` after bootstrapping.

---

## 🏗️ Defining Routes

Routes are defined statically using the `Router` class:

```php
Router::get('/api/v1/@me', [UserController::class, 'me']);
Router::post('/login', [AuthController::class, 'login'], [new RateLimitMiddleware(5, 60)]);
```

Each route can optionally include a **middleware array**.

You can also compose middleware sets from `App\Routing\MiddlewareGroups` to avoid duplication:

```php
use App\Routing\MiddlewareGroups;

$auth = MiddlewareGroups::auth();
Router::get('/api/v1/@me', [UserController::class, 'me'], $auth);
```

---

## 🧩 Supported Methods

- `Router::get($path, $handler, $middleware = [])`
- `Router::post($path, $handler, $middleware = [])`
- `Router::put($path, $handler, $middleware = [])`
- `Router::delete($path, $handler, $middleware = [])`

---

## 🔀 Dynamic Parameters

Routes may include parameters using the `:param` syntax:

```php
Router::get('/api/v1/users/:id', [UserController::class, 'show']);
```

Matches `/api/v1/users/123` and passes `123` as an argument to `UserController::show`.

---

## 🧱 Middleware

Middleware is executed before route handlers:

```php
Router::post('/logout', [AuthController::class, 'logout'], [
    new AuthMiddleware(),
    new CsrfMiddleware()
]);
```

Each middleware class must implement a `handle(Request $request): bool` method. Returning `false` halts the request.

---

## 🗂️ Versioned API Routes

Cardikit supports versioned route groups like `/api/v1`, `/api/v2`, etc.

Currently, the only supported version is `v1`.

Example of versioned route structure:

```php
Router::get('/api/v1/@me', [UserController::class, 'me']);
Router::get('/api/v2/@me', [UserController::class, 'me']);
```

This allows clean separation of evolving APIs while maintaining backwards compatibility.

---

## ⚙️ How It Works Internally

1. Parses the HTTP method and requested URI.
2. Converts any route parameters (`:id`) into regex.
3. Matches registered routes.
4. Applies middleware (if any).
5. Calls controller methods or closures.
6. Outputs result (JSON or raw response).
7. Sends `404` if no match is found.

See `app/Core/Router.php` for source code.

---

## 🧪 Example Routes

```php
Router::post('/register', [AuthController::class, 'register'], [new RateLimitMiddleware(5, 60)]);
Router::post('/login', [AuthController::class, 'login'], [new RateLimitMiddleware(5, 60)]);
Router::post('/logout', [AuthController::class, 'logout'], [new AuthMiddleware(), new CsrfMiddleware()]);
Router::get('/@me', [UserController::class, 'me'], [new AuthMiddleware()]);
Router::get('/csrf-token', [AuthController::class, 'csrfToken']);
```

---

## 📚 Related

- [🧭 Controllers](./controllers.html)
- [🧩 Middleware](./middleware.html)
- [🧩 Models](./models.html)

---
