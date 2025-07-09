---
layout: home
title: Models
nav_order: 5
parent: Backend
grandparent: Developers
---

# 🧩 Models

Models in Cardikit represent data structures that are persisted to the database. They provide a way to map data from the database to objects and vice versa.

---

## 🏗️ Base Model

All models inherit from:

```php
abstract class Model
```

This base class provides shared functionality such as database connection and record lookup.

**🔌 Key Properties**

| Property     | Type      | Description                                                           |
|-----------|--------------|--------------------------------------------------------|
| `$db`    | PDO | The database connection instance |
| `$table`    | string | The table name this model interacts with |

**🔍 findBy()**

```php
public function findBy(string $column, mixed $value): ?array
```

Finds the first record where `$column = $value`. Returns `null` if no record is found.

---

## 👤 Example: User Model

```php
namespace App\Models;

use App\Core\Database;
use PDO;

class User extends Model
{
    protected string $table = 'users';

    public static function create(array $data): bool
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password)
            VALUES (:name, :email, :password)
        ");

        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);
    }

    public static function findByEmail(string $email): ?array
    {
        return (new static())->findBy('email', $email);
    }

    public static function findById(int $id): ?array
    {
        return (new static())->findBy('id', $id);
    }

    public static function findLoggedInUser(): ?array
    {
        $user = (new static())->findBy('id', $_SESSION['user_id']);

        return [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at'],
        ];
    }
}
```

---

## 📋 Common Patterns

- `create(array $data)`: Insert a new row into the database.
- `findByEmail($email)`: Find a user by email.
- `findById($id)`: Find a user by ID.
- `findLoggedInUser()`: Utility for session-based access.

---

## 🧠 Notes

- Each model defines its `$table` string
- Model methods can encapsulate business logic and data transformation
- You can expand the base model with more shared utilities later (e.g. `all()`, `update()`, `delete()`)

---

## 📚 Related

- [📦 Migrations](./migrations.html)
- [🚀 Controllers](./controllers.html)
- [🔧 Config](./config.html)

---
