---
layout: home
title: Routing
nav_order: 1
parent: Frontend
grandparent: Developers
---

# 🔀 Routing Overview

This page documents the client-side routing setup used in the Cardikit frontend, built with React Router and structured for public (guest) and private (authenticated) access.

---

## 🧠 Route Philosophy

Cardikit separates routes into:

- **Guest Routes** - accessible only if the user is not logged in
- **Private Routes** - accessible only if the user *is* logged in

This ensures users are redirected appropriately based on authentication status.

---

## 🧩 Route Configuration

Defined inside `AppRoutes.tsx`, the app uses `react-router-dom`'s `<Routes>` and `<Route>` components along with custom wrappers:

**Example Route:**

```tsx
<Route path="/example" element={<Example />} />
```

The `Example` component is rendered when the user navigates to `/example`.

---

## 🛡️ Route Guards

**🔓 `GuestRoute`**

- Allows access only if the user is **not authenticated**
- Redirects authenticated users to `/dashboard`
- Useful for `/login` and `/register` routes
- All guest routes are wrapped with this guard

**Example Guest Route:**

```tsx
<Route element={<GuestRoute />}>
    <Route path="/login" element={<Login />} />
</Route>
```

**🔒 `PrivateRoute`**

- Allows access only if the user **is authenticated**
- Redirects unauthenticated users to `/login`
- Useful for `/dashboard` and other private routes

**Example Private Route:**

```tsx
<Route element={<PrivateRoute />}>
    <Route path="/dashboard" element={<Dashboard />} />
</Route>
```

---

## 🧪 Testing

Each route guard is unit tested to ensure proper redirection logic and fallback behavior during loading states.

---
