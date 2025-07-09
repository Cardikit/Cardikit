---
layout: home
title: CSRF
nav_order: 2
parent: API
grand_parent: Developers
---

# 🛡️ CSRF Protection API

The CSRF Protection API provides a way to protect against cross-site request forgery (CSRF) attacks.

## General Info

- **CSRF Protection:** Required for all authenticated `POST`, `PUT`, and `DELETE` requests.
- This API endpoint will return a token for use in the `X-CSRF-Token` header.
- Use `X-CSRF-Token` header for protected routes.

---

## Endpoints

### 🟡 GET `/csrf-token`

- Returns a token for use in the `X-CSRF-Token` header.
- Creates a session to ensure incoming requests are from a valid source.

**✅ Successful Response:**

- `200 OK` - CSRF token is created.

```json
{
    "csrf_token": "a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6"
}
```

**📌 Notes:**

- Use this token in the `X-CSRF-Token` header for protected routes.
- You will receive a `419 Authentication Timeout` error if the token is invalid or missing.

---
