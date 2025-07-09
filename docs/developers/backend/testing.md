---
layout: home
title: Testing
nav_order: 9
parent: Backend
grandparent: Developers
---

# ✅ Testing

Cardikit uses **[Pest](https://pestphp.com)** as its testing framework for writing elegant, expressive tests.

Tests can be executed using the built-in CLI command:

```bash
./cardikit test
```

---

## 📂 Example Test

Create your tests inside the `tests/` directory. Here's a simple example:

```php
<?php

test('example', function () {
    expect(true)->toBeTrue();
});
```

---

## 🧪 Writing Testable Code

To ensure maintainability and readability, your code should be structured with **testability** in mind:

**✅ Principles**

| Principle              | Description                                                         |
|------------------------|---------------------------------------------------------------------|
| **Separation of Concerns**          | Avoid mixing logic, validation, and DB access in one method     |
| **Dependency Injection**          | Inject dependencies so they can be replaced or mocked during tests     |
| **Pure Functions**          | Write functions that take input and return output without side effects     |
| **Return Values**          | Avoid direct `echo`/`die` in logic, use `return` instead     |

---

## 🔄 Integration vs Unit Testing

| Type          | Focus         | Pros          | Cons          |
|---------------|---------------|---------------|---------------|
| Unit Tests    | Isolated methods, mocks for dependencies   | Fast, targeted feedback          | May miss real-world issues |
| Integration Tests    | Full flow including database or session   | Tests actual application behavior          | Slower, more setup/cleanup needed |

> **🔍 Best Practice:** Use unit tests for most logic and only write integration tests when needed(e.g., for full request/response flow).

---

## 🚦 Test Directory Structure

```bash
/tests
  └── Feature/
  └── Unit/
  └── ExampleTest.php
```

Organize tests by type or feature.

---

## 🔗 Related

- [Pest](https://pestphp.com)
- [CLI](./cli.html)
- [Validation](./validation.html)

---
