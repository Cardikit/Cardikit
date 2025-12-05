---
layout: home
title: User
nav_order: 3
parent: API
grand_parent: Developers
---

# 👤 User API

The User API provides information about users.

---

## Endpoints

### 🟣 GET `/@me`

Returns information about the currently authenticated user.

**🛡️ Middleware Requirements:**

| Middleware         | Description                                                            |
|--------------------|------------------------------------------------------------------------|
| `Auth` | The user must be logged in with an active session. See [🔐 Authentication API](./authentication.html).                     |

**✅ Successful Response:**

- `200 OK` - User information is retrieved.

```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2022-01-01T00:00:00Z",
    "updated_at": "2022-01-01T00:00:00Z"
}
```

**❌ Error Response:**

- `401 Unauthorized` – User is not logged in. See [🔐 Authentication API](./authentication.html).

**📌 Notes:**

- This endpoint is often used to **hydrate frontend state** after a page reload or app boot.
- A `401` response indicates no active session exists - the user may need to log in again.

---

### 🟡 PUT `/@me`

Update the authenticated user's profile.

**🛡️ Middleware Requirements:**

| Middleware | Description |
|------------|-------------|
| `Auth` | Active session required. |
| `CSRF` | CSRF token required (`X-CSRF-Token`). |

**Request Body (partial updates allowed, but current password is required):**

```json
{
  "name": "New Name",
  "email": "new@example.com",
  "password": "newpassword",
  "password_confirmation": "newpassword",
  "current_password": "your-current-password"
}
```

Validation highlights:

- `current_password`: required to make any change.
- `name`: required if provided, 2–50 chars.
- `email`: required if provided, valid email, must be unique.
- `password`: required if provided, min 8 chars, must match confirmation.

**✅ Successful Response:**

- `200 OK`

```json
{
  "message": "Account updated successfully",
  "user": {
    "id": 1,
    "name": "New Name",
    "email": "new@example.com"
  }
}
```

**❌ Error Responses:**

- `401 Unauthorized` – Not logged in or invalid `current_password`.
- `422 Unprocessable Entity` – Validation errors (returns `errors` object).
- `500 Internal Server Error` – Update failed.

---

### 🔴 DELETE `/@me`

Delete the authenticated user's account.

**🛡️ Middleware Requirements:**

| Middleware | Description |
|------------|-------------|
| `Auth` | Active session required. |
| `CSRF` | CSRF token required (`X-CSRF-Token`). |

**Request Body:**

```json
{
  "password": "your-current-password"
}
```

**✅ Successful Response:**

- `200 OK`

```json
{ "message": "Account deleted" }
```

**❌ Error Responses:**

- `401 Unauthorized` – Not logged in or incorrect password.
- `422 Unprocessable Entity` – Missing password.
- `500 Internal Server Error` – Delete failed.

---

## 📚 Related

- [🔐 Authentication API](./authentication.html)
