---
layout: home
title: Migrations
nav_order: 6
parent: Backend
grandparent: Developers
---

# 📦 Migrations

Cardikit uses a simple migration system for creating and managing your database schema.

Migrations are PHP files that return an anonymous class extending the `Migration` base class. Each migration defines `up()` and `down()` methods to apply and rollback changes.

---

## 📂 Migration Files

Migration files are stored in the `database/migrations/` directory. They must return a class with `up()` and `down()` methods.

### Example

```php
use App\Core\Migration;

return new class extends Migration {
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )"
        );
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS users");
    }
};
```

---

## 🚀 Running Migrations

Cardikit provides CLI commands to manage your database schema.

**➕ `migrate`**

Creates all tables defined in the `up()` methods of your migrations.

```bash
./cardikit migrate
```

✅ Automatically runs inside the Docker container if Docker is used in the `.env`.

---

**🔄 `rollback`**

Drops all tables by calling each migration's `down()` method.

```bash
./cardikit rollback
```

⚠️ **Warning**: This will remove all data and tables. Use with caution in production environments.

- See [Cli](./cli.html) for more information about CLI commands.
- See [Config](./config.html) for more information about configuration files.

---

## 🛠️ Migration Base Class

All migration classes extend:

```php
abstract class Migration {
    protected function execute(string $sql): void
}
```

Use `$this->execute(...)` to run raw SQL queries inside `up()` and `down()`.

---

## 🧠 Best Practices

- Each migration file should focus on one logical change (e.g., creating one table).
- Use clear, timestamped filenames (optional, but recommended for future ordering).
- Keep backups before running `rollback`.

---

## 📚 Related

- [🧩 Models](./models.html)
- [🚀 Controllers](./controllers.html)
- [🛡️ Middleware](./middleware.html)
