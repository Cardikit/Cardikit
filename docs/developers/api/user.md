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
