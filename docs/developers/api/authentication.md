---
layout: home
title: Authentication
nav_order: 1
parent: API
grand_parent: Developers
---

# 🔐 Authentication API

Cardikit uses **session-based authentication** with CSRF protection and rate limiting.

## General Info

- **Auth method:** Session-based
- **Rate limiting:** 5 requests per minute per IP
- **CSRF Protection:** 
  - Required for `POST /logout`
  - Obtainable via `GET /csrf-token`
  - Use `X-CSRF-Token` header for protected routes

---

## Endpoints

### 🟣 POST `/register`

Registers a new user.

**Request Body:**

```json
{
  "name": "John Doe",
  "email": "John@example.com",
  "password": "securepassword",
  "password_confirmation": "securepassword"
}
```

**🔐 Validation Rules:**

| Field     | Rules                                                                 |
|-----------|-----------------------------------------------------------------------|
| `name`    | **Required.** Must be a **string** between **2 and 10 characters** long. |
| `email`   | **Required.** Must be a **valid email** and **unique** in the `users` table. |
| `password`| **Required.** Must be a **string** with **minimum 8 characters**. Must match the `password_confirmation` field. |

**✅ Successful Response:**

- `201 Created` – Successful registration

**❌ Error Response:**

- `422 Unprocessable Entity` – One or more validation rules failed.
- `429 Too Many Requests` – Rate limit exceeded.

---

### 🟢 POST `/login`

Logs in an existing user.

**Request Body:**

```json
{
  "email": "john@example.com",
  "password": "securepassword"
}
```

**🔐 Validation Rules:**

| Field     | Rules                                                                 |
|-----------|-----------------------------------------------------------------------|
| `email`   | **Required** and must be a **valid email**. |
| `password`| **Required** and must be a **string**. |

**✅ Successful Response:**

- `200 OK` – Authenticated, session cookie set.

**❌ Error Response:**

- `401 Unauthorized` – Invalid credentials.
- `422 Unprocessable Entity` – One or more validation rules failed.
- `429 Too Many Requests` – Rate limit exceeded.

---

### 🔴 POST `/logout`

Logs out the current session. Requires CSRF protection.

**📋 Headers:**

```
X-CSRF-Token: your_csrf_token_here
```

**🛡️ Middleware Requirements:**

| Middleware         | Description                                                            |
|--------------------|------------------------------------------------------------------------|
| `Auth` | The user must be logged in with an active session.                     |
| `CSRF`  | The request must include a **valid CSRF token** or it will be rejected. |

**✅ Successful Response:**

- `200 OK` – Logged out.

**❌ Error Response:**

- `401 Unauthorized` – Invalid credentials.
- `419 Authentication Timeout` – CSRF token missing or invalid.

---

## Flow Overview

```text
1. Client: POST /register
2. Client: POST /login
3. Client: GET /csrf-token
4. Client: POST /logout with X-CSRF-Token header
```
