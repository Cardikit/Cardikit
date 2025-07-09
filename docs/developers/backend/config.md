---
layout: home
title: Config
nav_order: 8
parent: Backend
grandparent: Developers
---

# ⚙️ Configuration

Cardikit is configured via a `.env` file located in the root of your project. This file defines environment-specific settings.

> 💡 A starter `.env.example` file is included by default. Copy it to `.env` and modify as needed:
>
> ```bash
> cp .env.example .env
> ```

---

## 📜 Environment Variables

| Variable              | Description                                                         | Example            |
|------------------------|---------------------------------------------------------------------|--------------------|
| `SERVER_PORT`          | Port your application should run on                                | `80`               |
| `USE_DOCKER`           | Whether CLI commands should run inside Docker                      | `true` or `false`  |
| `DOCKER_CONTAINER`     | Name of your Docker container used by the CLI                      | `cardikit_server`  |
| `MYSQL_HOST`           | MySQL hostname or container name                                   | `db`               |
| `MYSQL_DATABASE`       | MySQL database name                                                | `cardikit`         |
| `MYSQL_ROOT_PASSWORD`  | Root password for MySQL                                            | `password`         |
| `MYSQL_USER`           | App-specific MySQL user                                            | `cardikit`         |
| `MYSQL_PASSWORD`       | Password for the app-specific MySQL user                           | `password`         |

---

## 📁 Example `.env`

```dotenv
SERVER_PORT=80

# Set false if not using Docker
USE_DOCKER=true
DOCKER_CONTAINER=cardikit_server

MYSQL_HOST=db
MYSQL_DATABASE=cardikit
MYSQL_ROOT_PASSWORD=password
MYSQL_USER=cardikit
MYSQL_PASSWORD=password
```

---

## 🧠 Notes

- `USE_DOCKER=true` enables Docker passthrough for CLI tools like `./cardikit`.

---

## 📚 Related

- [CLI](./cli.html)
- [Migrations](./migrations.html)
- [Router](./router.html)

---
