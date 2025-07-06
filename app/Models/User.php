<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    public static function create(array $data): bool
    {
        $pdo = Database::connect();

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");

        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);
    }
}
