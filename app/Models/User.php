<?php

namespace App\Models;

/**
* User model contains methods to interact with the users table.
*
* @package App\Models
*
* @since 0.0.1
*/
class User extends Model
{
    /**
    * The SQL table associated with the User model
    *
    * @var string
    *
    * @since 0.0.2
    */
    protected string $table = 'users';

    /**
    * The columns that are fillable.
    *
    * @var array
    *
    * @since 0.0.3
    */
    protected array $fillable = ['name', 'email', 'password', 'role'];

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

    /**
    * Finds the logged in user.
    *
    * @return array|null
    *
    * @since 0.0.1
    */
    public static function findLoggedInUser(): ?array
    {
        $user = (new static())->findBy('id', $_SESSION['user_id']);

        return [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at']
        ];
    }

    /**
    * Finds a user by id.
    *
    * @param int $id
    *
    * @return array|null
    *
    * @since 0.0.1
    */
    public static function findById(int $id): ?array
    {
        return (new static())->findBy('id', $id);
    }

    /**
    * Hash sensitive data before creating a record.
    *
    * @param array $data
    *
    * @return array
    *
    * @since 0.0.1
    */
    protected function beforeCreate(array $data): array
    {
        return $this->hashPasswordIfPresent($data);
    }

    /**
    * Hash sensitive data before updating a record.
    *
    * @param array $data
    *
    * @return array
    *
    * @since 0.0.1
    */
    protected function beforeUpdate(array $data): array
    {
        return $this->hashPasswordIfPresent($data);
    }

    /**
    * Hashes the password if present.
    *
    * @param array $data
    *
    * @return array
    *
    * @since 0.0.1
    */
    protected function hashPasswordIfPresent(array $data): array
    {
        if (array_key_exists('password', $data)) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }
}
