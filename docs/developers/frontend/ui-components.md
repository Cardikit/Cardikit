---
layout: home
title: UI Components
nav_order: 3
parent: Frontend
grandparent: Developers
---

# 🧩 UI Component Overview

UI components are the building blocks of the frontend, providing reusable, styled, and composable elements that power forms, buttons, modals, and more. They abstract Tailwind classes, accessibility,  ons, and logic into clean interfaces.

---

## 🔤 Example: `Input` Component

A custom input component layered over the base `InputCN` from the UI library (shadcn)[https://ui.shadcn.com/docs/components/input] with additional styles and features.

### ✨ Features

- Left-side icon support (`startAdornment`)
- Toggleable password visibility for inputs of type `password`
- Error state display under the field
- Tailwind + Inter font for visual consistency

---

### 📦 Usage

```tsx
<Input
    type="email"
    startAdornment={<IoIosMail />}
    placeholder="Enter your email"
    error={errors.email?.message}
/>
```

---

## 📁 Component Locations

- Each feature will have their own components.
- Global components are located in `src/components/`
- Feature-specific components are located in `src/features/{feature}/components/`
- ShadCN UI components are a recommended starting point for component composition.

## 🧪 Testing

Each component should have unit tests verifying:

- Conditional rendering such as `loading` and `error` states

---
