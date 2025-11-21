<?php

namespace App\Models;

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
    * The columns that are fillable
    *
    * @var array $fillable
    *
    * @since 0.0.1
    */
    protected array $fillable = ['name', 'user_id'];

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
