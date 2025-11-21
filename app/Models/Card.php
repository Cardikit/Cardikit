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

    protected array $fillable = ['name', 'user_id'];

    public static function delete(int $id): bool
    {
        $pdo = Database::connect();

        $stmt = $pdo->prepare("DELETE FROM cards WHERE id = :id");

        return $stmt->execute([
            'id' => $id,
        ]);
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
