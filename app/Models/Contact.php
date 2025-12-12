<?php

namespace App\Models;

/**
* Represents a saved contact share from a visitor.
*
* @package App\Models
*
* @since 0.0.5
*/
class Contact extends Model
{
    protected string $table = 'contacts';

    protected array $fillable = [
        'card_id',
        'card_slug',
        'name',
        'email',
        'phone',
        'source_url',
        'user_agent',
        'metadata',
    ];

    /**
    * Find a contact with the exact same details for the same card.
    */
    public function findDuplicate(?int $cardId, ?string $cardSlug, ?string $name, ?string $email, ?string $phone): ?array
    {
        $sql = "
            SELECT * FROM {$this->table}
            WHERE (card_id = :card_id OR card_slug = :card_slug)
              AND name <=> :name
              AND email <=> :email
              AND phone <=> :phone
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'card_id' => $cardId,
            'card_slug' => $cardSlug,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ?: null;
    }
}
