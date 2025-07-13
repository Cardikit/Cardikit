<?php

namespace App\Models;

use App\Core\Database;

/**
* Card model contains methods to interact with the cards table.
*
* @package App\Models
*
* @since 0.0.1
*/
class Card extends Model
{
    protected string $table = 'cards';

    /**
    * Stores card data in the database.
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

        $stmt = $pdo->prepare("INSERT INTO cards (name, user_id) VALUES (:name, :user_id)");

        return $stmt->execute([
            'name' => $data['name'],
            'user_id' => $data['user_id'],
        ]);
    }

    public static function update(array $data): bool
    {
        $pdo = Database::connect();

        $stmt = $pdo->prepare("UPDATE cards SET name = :name WHERE id = :id");

        return $stmt->execute([
            'name' => $data['name'],
            'id' => $data['id'],
        ]);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::connect();

        $stmt = $pdo->prepare("DELETE FROM cards WHERE id = :id");

        return $stmt->execute([
            'id' => $id,
        ]);
    }

    public static function find(int $id): ?array
    {
        return (new static())->findBy('id', $id);
    }

    /**
    * Returns a user's cards
    *
    * @param int user's id
    *
    * @return array|null
    *
    * @since 0.0.1
    */
    public static function userCards(int $id): ?array
    {
        return (new static())->findAllBy('user_id', $id);
    }
}
