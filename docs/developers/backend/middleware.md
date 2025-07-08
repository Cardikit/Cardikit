---
layout: home
title: Middleware
nav_order: 2
parent: Backend
grandparent: Developers
---

# 🛡️ Middleware

Middleware in **Cardikit** is used to inspect and filter incoming HTTP requests *before* they reach your controller logic. Each middleware class implements a `handle()` method that returns a `bool`.

If a middleware returns `false`, the request is halted and a response is sent immediately.

---

## 🔧 How Middleware Works

When defining routes, you can pass an array of middleware instances as the third parameter:

```php
Router::post('/logout', [AuthController::class, 'logout'], [
    new AuthMiddleware(),
    new CsrfMiddleware()
]);
```

During dispatch, each middleware's `handle()` method is executed in order. If any return `false`, routing stops and the response is immediately returned.

See more about [🧭 routing](./router.html).

---

## 🧪 Example: `AuthMiddleware`

```php
<?php

namespace App\Middleware;

use App\Core\Response;

class AuthMiddleware
{
    public function handle(): bool
    {
        if (! isset($_SESSION['user_id'])) {
            Response::json(['error' => 'Unauthorized'], 401);
            return false;
        }

        return true;
    }
}
```

**Purpose:**

Ensures the user is authenticated by checking for `$_SESSION['user_id']`.

**Failure Response:**

```json
{
    "error": "Unauthorized"
}
```

---

## ✨ Writing Custom Middleware

To write your own middleware:

1. Create a class in `App\Middleware`
2. Implement a `handle(): bool` method
3. Return `false` to short-circuit and respond immediately

```php
class MyCustomMiddleware
{
    public function handle(): bool
    {
        // logic...
        return true;
    }
}
```

Then attach it to a route:

```php
Router::get('/secure', [SecureController::class, 'view'], [
    new MyCustomMiddleware()
]);
```

---

## 📚 Related

- [🧭 Router](./router.html)
- Controllers
- CSRF

---
