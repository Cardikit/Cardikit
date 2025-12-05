---
layout: home
title: Backend
nav_order: 2
parent: Developers
has_children: true
---

# ⚙️ Backend Overview

This document provides a complete overview of the backend architecture, including the request lifecycle, folder structure, and code organization philosophy used throughout the application.

---

## 🔁 Request Lifecycle

Here's what happens from the moment a request hits the server to when a response is returned:

1. **[Router](./router.html)**:
    - The `Router` class matches incoming HTTP requests to a defined route and applies any attached middleware.

2. **[Middleware](./middleware.html)**:
    - Middleware classes can intercept the request before it hits the controller. Examples include authentication checks, CSRF verification, etc.

3. **[Controller](./controllers.html)**:
    - If all middleware pass, the corresponding controller is invoked. The controller handles validation, business logic, and calls models as needed.

4. **[Validation](./validator.html)**:
    - The `Validator` class checks that incoming data meets defined rules. Errors are returned as JSON responses if validation fails.

5. **[Model Interaction](./models.html)**:
    - Models handle all communication with the database. Queries are abstracted behind model methods.

6. **Response**:
    - A response is returned using the `Response` class, typically as a JSON object with a proper HTTP status code.

---

```bash
App/
├── Controllers/     # Request handlers for your routes
├── Core/            # Core classes (Router, Request, Response, Validator, etc.)
├── Middleware/      # Middleware logic (e.g., AuthMiddleware)
├── Models/          # Database abstraction and queries
├── Services/        # Domain services (AuthService, CardService, etc.)
├── Views/           # (If applicable) for server-rendered pages
├── Config/          # Environment and configuration settings
database/
└── migrations/      # Table creation scripts with up/down methods
tests/
└── Unit/            # Unit tests
└── Feature/         # Integration tests
public/
└── index.php        # Entry point
.env.example         # Sample environment variables
.cardikit            # CLI tool
```

---

##  🧼Code Organization Philosophy

- **Explicit & Modular**: Each class and file has one clear responsibility. Core logic is centralized, avoiding duplication.
- **Minimal Global State**: No unnecessary globals. Data is passed explicitly to functions and constructors.
- **PSR-4 Autoloading**: All classes are namespaced and autoloaded by Composer.
- **Validation First**: Data is validated at the boundary (controllers), not deep inside the logic.
- **Testability**: Classes are designed to be unit-tested. The system supports mocking and avoids hidden side effects.

---

## 🧽 Linting & Standards

- PSR-12 for PHP code style
- Consistent docblocks with `@package`, `@since`, and parameter annotations
- Class names use PascalCase, file names match class names
- Methods and variables use camelCase
- Align multiline arrays and parameters for readability
- Limit neting to 2-3 levels

---
