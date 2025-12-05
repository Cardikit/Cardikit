---
layout: home
title: Response
nav_order: 9
parent: Backend
grandparent: Developers
---

# 📤 Response

The `Response` helper centralizes HTTP output. Most controllers and middleware return JSON, but you can also render HTML views.

---

## 🚀 JSON

```php
use App\Core\Response;

Response::json(['message' => 'ok'], 200);
```

- Sets status code (default `200`).
- Sends `Content-Type: application/json`.
- Encodes the provided array to JSON.

---

## 📝 HTML

```php
Response::html('<p>Hello</p>', 200);
```

- Sets status code and `Content-Type: text/html; charset=utf-8`.
- Echoes the provided string.

---

## 🖼️ View

```php
Response::view(__DIR__ . '/views/landing.php', ['name' => 'Cardikit']);
```

- Extracts the `$data` array into the view scope.
- Buffers the output and sends it as HTML.
- Falls back to a 500 if the view file is missing.

---

## 📚 Related

- [🧭 Controllers](./controllers.html)
- [🧭 Router](./router.html)
- [🛡️ Middleware](./middleware.html)
