---
layout: home
title: CLI
nav_order: 7
parent: Backend
grandparent: Developers
---

# 🧰 Cardikit CLI

The Cardikit CLI provides helpful utilities for managing your project through terminal commands. It supports migrations, rollbacks, and running tests. The CLI auto-detects Docker and runs commands inside the container if one is active.

---

## 📜 Available Commands

| Command         | Description                                      |
|-----------------|--------------------------------------------------|
| `test`          | Runs the test suite using [PestPHP](https://pestphp.com). |
| `migrate`       | Runs all migration files to set up database tables. |
| `rollback`      | Rolls back all database tables.                  |

Run a command like this:

```bash
./cardikit migrate
```

if `USE_DOCKER=true` in your `.env` file, this will be executed in the Docker container.

---

## 🧪 `test`

Runs your test suite via PestPHP:

```bash
./cardikit test
```

You can pass additional arguments directly to Pest:

```bash
./cardikit test --filter=SomeFeature
```

⚠️ It is important to run these tests before requesting a PR.

---

## 🏗️ `migrate`

Applies all migrations by calling their `up()` methods:

```bash
./cardikit migrate
```

✅ Outputs each migration being executed.

---

## 🗑️ `rollback`

Reverses all migrations by calling the `down()` method of each:

```bash
./cardikit rollback
```

⚠️ Warning: This will drop all tables. Do not run in production unless you’re absolutely sure.

---

## ⚙️ How It Works

Cardikit's CLI is defined in:

```bash
./cardikit
```

It checks your environment:

- If Docker is enabled (`USE_DOCKER=true`) in your `.env` file, and you're **not** inside a container, it runs the command in the container defined by `DOCKER_CONTAINER`.
- Otherwise, it executes natively.

The CLI is powered by a lightweight Kernel:

```php
// Kernel.php
protected array $commands = [
    'test' => Commands\TestCommand::class,
    'migrate' => Commands\MigrateCommand::class,
    'rollback' => Commands\RollbackCommand::class
];
```

Each command class has a `handle()` method that executes the logic.

---

## 🧠 Tips

- Keep migrations idempotent. Use `IF EXISTS` and `IF NOT EXISTS` clauses.
- Use `test` regularly to validate changes.
- You can add custom commands by extending the `Kernal`.

---

## 📚 Related

- [🏗️ Migrations](./migrations.html)
- [🧪 PestPHP](https://pestphp.com/)
- [🐳 Docker](https://www.docker.com/)
- Testing
- Config

---
