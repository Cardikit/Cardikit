---
layout: home
title: Validator
nav_order: 4
parent: Backend
grandparent: Developers
---

# ✅ Validator

The **Validator** class in Cardikit provides flexible rule-based validation for user input. Errors are collected and can be returned to the client for feedback.

---

## 🔧 Usage Example

```php
use App\Core\Validator;

$validator = new Validator();
$isValid = $validator->validate($data, [
    'name' => 'required|min:2|max:10|type:string',
    'email' => 'required|email',
    'password' => 'required|min:8|type:string|confirmed'
]);

if (!$isValid) {
    $errors = $validator->errors();
    // Handle error response
}
```

---

## 📜 Available Validation Rules

| Rule     | Description                                                                 |
|-----------|-----------------------------------------------------------------------|
| `required`    | Field must be present and not empty. |
| `email`    | Must be a valid email format. |
| `min:{length}`    | Must be at least `{length}` characters long. |
| `max:{length}`    | Must be no more than `{length}` characters long. |
| `type:{type}`    | Enforces value type. Supports: `string`, `int`, `bool`, `array`. |
| `confirmed`    | Must match a corresponding `{field}_confirmation` field. |
| `unique:{Model}:{column}`    | Must not already exist in the given model column. Uses `findBy`. |

⚠️ If the `column` is not provided, it defaults to the field name.

---

## 📜 Unique Rule Example

When using the unique rule, the model must be injected as a dependency:

```php
use App\Core\Validator;
use App\Models\User;

$validator = new Validator([User::class => new User()]); // Inject models for unique rule
$isValid = $validator->validate($data, [
    'email' => 'required|email|unique:App\Models\User:email'
]);

if (!$isValid) {
    $errors = $validator->errors();
    // Handle error response
}
```

The validator relies on the model for any unique checks.

---

## 📤 Output Example

```json
{
    "errors": {
        "name": ["Name is too short"],
        "email": ["Email is invalid", "Email is taken"],
        "password": ["Password does not match confirmation"]
    }
}
```

---

## 🔎 How It Works

1. The rule string (e.g. `required|min:3|type:string`) is split into parts.
2. Each rule is evaluated by its dedicated method.
3. If validation fails, an error is added to the `errors` array.
4. If the `$errors` array is empty after validation, the data is considered valid.

---

## 📝 Related

- [🧭 Controllers](./controllers.html)
- [🧭 Router](./router.html)
- [📡 Middleware](./middleware.html)
