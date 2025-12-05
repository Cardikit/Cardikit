---
layout: home
title: Cards
nav_order: 4
parent: API
grand_parent: Developers
---

# 📇 Cards & QR API

Endpoints for managing cards, their items, images, themes, and QR codes.

> All endpoints are under `/api/v1` and require an authenticated session. Mutating requests also require a CSRF token (`X-CSRF-Token`).

---

## Common Validation

Card payload fields:

| Field          | Rules |
|----------------|-------|
| `name`         | Required, string, 2–50 chars, unique per user when changed. |
| `color`        | Required, hex color (`#RGB` or `#RRGGBB`). |
| `theme`        | Optional, string, max 50, must be one of the available theme slugs. |
| `banner_image` | Optional, base64/data URI string or `null` to clear. |
| `avatar_image` | Optional, base64/data URI string or `null` to clear. |
| `card_items`   | Array of items (see below). |

Card item fields (each element):

| Field    | Rules |
|----------|-------|
| `type`   | Required, one of the supported item types (name, phone, email, link, etc.). |
| `value`  | Required, string, 2–255 chars. |
| `label`  | Optional, string, max 255. |
| `position` | Optional, integer (ordering). |
| `meta`   | Optional, mixed. |

---

## 🟣 GET `/@me/cards`

List all cards for the authenticated user (includes items and images).

**Response:** `200 OK`

```json
[
  {
    "id": 1,
    "name": "My Card",
    "color": "#1D4ED8",
    "theme": "default",
    "banner_image": null,
    "avatar_image": null,
    "items": [ /* card items */ ]
  }
]
```

---

## 🟣 GET `/@me/cards/:id`

Fetch a single card by id (must belong to the authenticated user).

- `404` if not found, `401` if not owned.

---

## 🟢 POST `/@me/cards`

Create a new card.

**Middleware:** `Auth`, `CSRF`, rate limit (60 req/min default).

**Request Body:** See common validation above. `banner_image`/`avatar_image` accept base64/data URI to upload, `""` to delete, `null` to keep empty.

**Response:** `201 Created`

```json
{
  "message": "Card created successfully",
  "card": { /* created card with items/images */ }
}
```

**Errors:** `422` validation (returns `errors` object), `500` on failure.

---

## 🟠 PUT `/@me/cards/:id`

Update an existing card (must belong to user).

**Middleware:** `Auth`, `CSRF`.

**Request Body:** Same as create. `slug` is immutable and ignored.

**Response:** `200 OK`

```json
{
  "message": "Card updated successfully",
  "card": { /* updated card */ }
}
```

**Errors:** `404` not found, `401` unauthorized, `422` validation, `500` on failure.

---

## 🔴 DELETE `/@me/cards/:id`

Delete a card and associated QR/images (must belong to user).

**Middleware:** `Auth`, `CSRF`.

**Response:** `200 OK`

```json
{ "message": "Card deleted successfully" }
```

**Errors:** `404`, `401`.

---

## 🧾 POST `/@me/cards/:id/qr`

Regenerate the QR code for a card (optionally with a logo overlay).

**Middleware:** `Auth`, `CSRF`.

**Request Body:**

```json
{ "logo": "base64-or-data-uri-string" }
```

**Response:** `200 OK`

```json
{
  "message": "QR code generated",
  "card_url": "https://.../c/slug",
  "qr_image_url": "https://.../qrcodes/card-1-abc.png",
  "qr_image_path": "/path/on/server.png"
}
```

**Errors:** `404`, `401`, `422` (invalid logo), `500` (generation failure).

---

## 🎨 GET `/themes`

List available themes (slugs and metadata).

**Middleware:** `Auth` (rate limited).

**Response:** `200 OK`

```json
[
  { "slug": "default", "name": "Default", "description": "...", "version": "1.0.0" }
]
```

---

## 🌐 Public Cards

### 🟣 GET `/c/:slug`

Public, unauthenticated view of a card by slug. Returns HTML by default; returns JSON when `Accept: application/json` is provided.

**JSON Response Example:**

```json
{
  "id": 1,
  "name": "My Card",
  "items": [ /* public items */ ],
  "qr_image": "https://.../qrcodes/card-1.png"
}
```

**Errors:** `404` if not found.

---

## 📚 Related

- [🔐 Authentication](./authentication.html)
- [🛡️ CSRF](./csrf.html)
- [👤 User](./user.html)
