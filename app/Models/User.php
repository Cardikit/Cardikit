<?php

namespace App\Models;

use App\Core\Database;
use PDO;

/**
* User model contains methods to interact with the users table.
*
* @package App\Models
*
* @since 0.0.1
*/
class User extends Model
{
    protected string $table = 'users';

    /**
    * Stores user data in the database.
    *
    * @param array $data
    *
    * @return bool
    *
    * @since 0.0.1
    */
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

    /**
    * Finds a user by email.
    *
    * @param string $email
    *
    * @return array|null
    *
    * @since 0.0.1
    */
    public static function findByEmail(string $email): ?array
    {
        return (new static())->findBy('email', $email);
    }
}
