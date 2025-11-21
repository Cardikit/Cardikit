<?php

namespace App\Models;

/**
* Card Item model contains methods to interact with the card_items table.
*
* @package App\Models
*
* @since 0.0.1
*/
class Card extends Model
{
    protected string $table = 'card_items';

    /**
    * The columns that are fillable
    *
    * @var array $fillable
    *
    * @since 0.0.1
    */
    protected array $fillable = [
        'card_id',
        'type',
        'label',
        'value',
        'position',
        'meta',
    ];
}
