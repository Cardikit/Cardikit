---
layout: home
title: Frontend
nav_order: 1
parent: Developers
has_children: true
---

# 🎨 Frontend Overview

This document outlines the frontend architecture of the application, including its component structure, routing, state management, and architectural philosophies.

---

## 🧭 App Flow & Component Hierarchy

Here's what happens when a user interacts with the UI:

1. **AppEntry (`main.tsx`)**:
    - Initializes the React app, wraps it in context providers, and renders the router.

2. **Routing**:
    - Client-side routing is handled via React Router.
    - Public and private routes are wrapped with guards (`GuestRoute`, `PrivateRoute`).

3. **Layout Components**:
    - Top-level layout components (e.g. `AuthLayout`) define consistent page structure.

4. **Page Components**:
    - Each page (`Login`, `Register`, `Dashboard`, etc.) is a top-leavel route component.

5. **UI Components**:
    - Reusable UI primitives like `Input`, `Button`, and `Back` are used across the app.

6. **Hooks**:
    - Custom React hooks like `useLoginUser`, `useAuthenticatedUser`, etc. manage logic and data fetching.

7. **Context**:
    - Auth state is managed via React Context (`AuthContext`) and shared globally.

---

##  🗂 Folder Structure

```bash
src/
├── components/           # Reusable UI components (Input, Button, etc.)
├── contexts/             # React contexts (AuthContext, etc.)
├── features/             # Feature-specific logic (auth, dashboard, etc.)
│   └── auth/             
│       ├── components/   # Auth-specific components (Login, Register, etc.)
│       ├── hooks/        # Hooks like useLoginUser, useRegisterUser
│       ├── pages/        # Page-level components
│       └── validationSchema.ts
├── lib/                  # Utility libraries (e.g., Axios instance)
├── routes/               # Route definitions and guards (PrivateRoute, GuestRoute)
├── types/                # Global TypeScript types (e.g., User)
├── App.tsx               # Root component
├── main.tsx              # App entry point
├── vite.config.ts        # Vite configuration
tests/
└── ...                   # Vitest test files (unit & integration)
public/
└── index.html            # Static HTML entry point
```

---

## 🧼 Code Organization Philosophy

- **Modular Features**: Each domain (lik `auth`) contains its own components, hooks, and pages for better isolation.
- **Composable UI**: UI components are kept atomic and reusable. Larger components are built from smaller ones.
- **Hook-Driven Logic**: Data fetching and business logic live in custom hooks.
- **Context-Scoped State**: Auth and session state live in `AuthContext` and are consumed via `useAuth()`.
- **Type-Safe**: TypeScript enforces correctness across components, hooks, and API responses.

---

## 🎨 Styling Standards

- **Tailwind CSS**:
    - Utility-first CSS for fast and consistent styling.
    - Classnames use logical grouping for readability.
- **Fonts & Colors**:
    - Inter font for consistent typography.
    - Custom color palette includes primary, secondary, etc.
- **Accessibility**:
    - Focus states, aria-labels, and semantic HTML are prioritized.

---

## 🧪 Testing Philosophy

- **Vitest + Testing Library** for unit and integration tests
- Coverage for components, hooks, and page logic
- Mocked API respones via `vi.mock()`
- Tests colocated with the feature being tested

---
