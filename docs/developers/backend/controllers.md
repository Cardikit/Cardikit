---
layout: home
title: Controllers
nav_order: 3
parent: Backend
grandparent: Developers
---

# 🧭 Controllers

Controllers in **Cardikit** are responsible for handling incoming HTTP requests and returning appropriate responses. They serve as the bridge between routes and business logic.

Each controller method corresponds to a specific action and is usually tied to a route in the [router](./router.html).

---

## 🛠️ How Controllers Work

Routes are mapped to controller methods using:

```php
Router::post('/login', [AuthController::class, 'login']);
```

This calls the `login()` method on the `AuthController` class when a POST request is made to `/login`.

See [🧭 routing](./router.html) for more information about defining routes.

---

## 🧪 Example: `AuthController`

Located at: App\Controllers\AuthController.php

**🔹`register(Request $request): void`**

Registers a new user after validating input.

- Uses the [Validator](./validator.html) to enforce rules on:
    - `name`: required, string, 2-10 characters
    - `email`: required, email format, must be unique
    - `password`: required, string, at least 8 characters, confirmed
- Saves the user to the database.
- Returns response.

Response:

- `201 Created` on success
- `422 Unprocessable Entity` on validation error

**🔹`login(Request $request): void`**

Authenticates an existing user.

- Uses the [Validator](./validator.html) to enforce rules on:
    - `email`: required, email format
    - `password`: required, string
- Verifies credentials with `password_verify()`.
- Sets session variables on success.
- Returns response.

Response:

- `200 OK` on success
- `401 Unauthorized` on authentication failure
- `422 Unprocessable Entity` on validation error

**🔹`logout(): void`**

Destroys the session and removes session cookies.

- Clears `$_SESSION` and cookie via `session_destroy()`.
- Returns response.

Response:

- `200 OK` on success

**🔹`csrfToken(): void`**

Generates a new CSRF token.

- Saves token to `$_SESSION['csrf_token']`
- Returns token in JSON

Response:

```json
{
    "csrf_token": "generated_token_here"
}
```

---

## ✨ Writing Your Own Controllers

1. Create a class in `App\Controllers`
2. Add public methods to handle specific routes
3. Accept `Request $request` if needed for body or params
4. Return output using the `Response` helper

Example:

```php
class PingController
{
    public function pong(Request $request): void
    {
        Response::json(['message' => 'pong']);
    }
}
```

---

## 📚 Related

- [🧭 Router](./router.html)
- [🛡️ Middleware](./middleware.html)
- [📜 Validator](./validator.html)
