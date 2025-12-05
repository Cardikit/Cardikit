---
layout: home
title: API
nav_order: 3
parent: Developers
has_children: true
---

# 📡 API Overview

Welcome to the Cardikit API documentation. This API powers the backend of Cardikit and is designed for use by frontend developers and backend integrators alike. It is **session-based**, uses **CSRF protection**, and enforces **rate limiting** to ensure secure and predictable behavior.

---

## 🌐 Base URL

```text
Production: https://yourdomain.com/api/v1
Development: http://localhost/api/v1
```

All endpoints described in this documentation are relative to the base URL.

---

## 🔑 Authentication

Cardikit uses **session-based authentication**. Once a user logs in, the session cookie will be maintained across requests.

- **CSRF Protection**: Required for all authenticated `POST`, `PUT`, and `DELETE` requests.
- **Rate Limiting**: All auath-related endpoints are limited to 5 requests per minute over IP.
- **Session-based**: Session cookies must be included with each request.

📄 see the full [Authentication API](./authentication.html) documentation.

---

## 📦 Available Sections

Explore the following parts of the API:

- [🔐 Authentication](./authentication.html) - Register, login, and logout users.
- [👤 User Info](./user.html) - Get and update user information.
- [📇 Cards & QR](./cards.html) - Manage cards, items, images, and QR codes.
- [🎨 Themes](./cards.html#-themes) - List available card themes.

---

## 📄 Request/Response Format

All requests and responses are in **JSON** format.

**✅ Success Example:**

```json
{
    "message": "Success",
    "data": {
        "id": 1,
        "name": "John Doe"
    }
}
```

**❌ Error Example (Validation Failure):**

```json
{
    "message": "Validation Failed",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

**❌ Error Example (Unauthorized):**

```json
{
    "message": "Unauthenticated"
}
```

---

## 🛠️ Developer Tips

- All requests should include the header:

```text
Accept: application/json
```

- For `POST`, `PUT`, and `DELETE` requests, include the CSRF header:

```text
X-CSRF-TOKEN: your_csrf_token_here
```

- Tools like **Postman** or **Insomnia** are recommended for testing.

---

🎉 Happy building!
