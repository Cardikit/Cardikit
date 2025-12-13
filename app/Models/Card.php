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
    /**
    * SQL table for Card model
    *
    * @var string $table
    *
    * @since 0.0.2
    */
    protected string $table = 'cards';

    /**
    * The columns that are fillable
    *
    * @var array $fillable
    *
    * @since 0.0.1
    */
    protected array $fillable = ['name', 'color', 'theme', 'user_id', 'qr_url', 'qr_image', 'slug'];

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

    /**
    * Returns a user's cards with their card items.
    *
    * @param int $userId
    *
    * @return array|null
    *
    * @since 0.0.1
    */
    public static function userCardsWithItems(int $userId): ?array
    {
        $instance = new static();

        $sql = "
            SELECT
                c.*,
                (SELECT image_url FROM card_images WHERE card_id = c.id AND type = 'banner' LIMIT 1) AS banner_image,
                (SELECT image_path FROM card_images WHERE card_id = c.id AND type = 'banner' LIMIT 1) AS banner_image_path,
                (SELECT image_url FROM card_images WHERE card_id = c.id AND type = 'avatar' LIMIT 1) AS avatar_image,
                (SELECT image_path FROM card_images WHERE card_id = c.id AND type = 'avatar' LIMIT 1) AS avatar_image_path,
                ci.id AS item_id,
                ci.card_id AS item_card_id,
                ci.type AS item_type,
                ci.label AS item_label,
                ci.value AS item_value,
                ci.position AS item_position,
                ci.meta AS item_meta,
                ci.created_at AS item_created_at,
                ci.updated_at AS item_updated_at
            FROM cards c
            LEFT JOIN card_items ci on ci.card_id = c.id
            WHERE c.user_id = :user_id
            ORDER BY c.id, ci.position, ci.id
        ";

        $stmt = $instance->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!$rows) {
            return null;
        }

        $cards = [];

        foreach ($rows as $row) {
            $cardId = (int) $row['id'];

            // Initialize card once
            if (!isset($cards[$cardId])) {
                $card = $row;

                // strip item columns from card (keep only card fields)
                unset(
                    $card['item_id'],
                    $card['item_card_id'],
                    $card['item_type'],
                    $card['item_label'],
                    $card['item_position'],
                    $card['item_meta'],
                    $card['item_created_at'],
                    $card['item_updated_at']
                );

                $card['items'] = [];
                $cards[$cardId] = $card;
            }

            // Attach item if there is on (LEFT JOIN -> can be null)
            if ($row['item_id'] !== null) {
                $cards[$cardId]['items'][] = [
                    'id' => (int) $row['item_id'],
                    'card_id' => (int) $row['item_card_id'],
                    'type' => $row['item_type'],
                    'label' => $row['item_label'],
                    'value' => $row['item_value'],
                    'position' => (int) $row['item_position'],
                    'meta' => $row['item_meta'],
                    'created_at' => $row['item_created_at'],
                    'updated_at' => $row['item_updated_at']
                ];
            }
        }

        // Re-index to a plain array
        return array_values($cards);
    }

    /**
     * Counts how many cards a user has.
     *
     * @param int $userId
     *
     * @return int
     *
     * @since 0.0.7
     */
    public static function countForUser(int $userId): int
    {
        $instance = new static();

        $stmt = $instance->db->prepare('SELECT COUNT(*) AS total FROM cards WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int) ($row['total'] ?? 0);
    }

    /**
    * Returns a single card with its items.
    *
    * @param int $cardId
    *
    * @return array|null
    *
    * @since 0.0.1
    */
    public static function findWithItems(int $cardId): ?array
    {
        return self::findWithItemsBy('id', $cardId);
    }

    /**
    * Returns a single card with its items by slug.
    *
    * @param string $slug
    *
    * @return array|null
    *
    * @since 0.0.2
    */
    public static function findWithItemsBySlug(string $slug): ?array
    {
        return self::findWithItemsBy('slug', $slug);
    }

    /**
    * Generate a unique slug for a card.
    *
    * @param int $length defaults to 12
    *
    * @return string
    *
    * @since 0.0.2
    */
    public static function generateUniqueSlug(int $length = 12): string
    {
        $instance = new static();

        for ($i = 0; $i < 5; $i++) {
            $slug = substr(bin2hex(random_bytes((int) ceil($length / 2))), 0, $length);
            if (!$instance->findBy('slug', $slug)) {
                return $slug;
            }
        }

        throw new \RuntimeException('Unable to generate unique card slug');
    }

    /**
     * Internal helper to load a card + items by column.
     *
     * @param string $column
     * @param mixed $value
     *
     * @return array|null
     *
     * @since 0.0.2
     */
    protected static function findWithItemsBy(string $column, mixed $value): ?array
    {
        if (!in_array($column, ['id', 'slug'], true)) {
            throw new \InvalidArgumentException('Unsupported lookup column');
        }
        $instance = new static();

        $sql = "
            SELECT
                c.*,
                (SELECT image_url FROM card_images WHERE card_id = c.id AND type = 'banner' LIMIT 1) AS banner_image,
                (SELECT image_path FROM card_images WHERE card_id = c.id AND type = 'banner' LIMIT 1) AS banner_image_path,
                (SELECT image_url FROM card_images WHERE card_id = c.id AND type = 'avatar' LIMIT 1) AS avatar_image,
                (SELECT image_path FROM card_images WHERE card_id = c.id AND type = 'avatar' LIMIT 1) AS avatar_image_path,
                ci.id AS item_id,
                ci.card_id AS item_card_id,
                ci.type AS item_type,
                ci.label AS item_label,
                ci.value AS item_value,
                ci.position AS item_position,
                ci.meta AS item_meta,
                ci.created_at AS item_created_at,
                ci.updated_at AS item_updated_at
            FROM cards c
            LEFT JOIN card_items ci ON ci.card_id = c.id
            WHERE c.{$column} = :value
            ORDER BY ci.position, ci.id
        ";

        $stmt = $instance->db->prepare($sql);
        $stmt->execute(['value' => $value]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!$rows) {
            return null;
        }

        // First row contains the card fields
        $first = $rows[0];
        $card = $first;

        // strip out item fields
        unset(
            $card['item_id'],
            $card['item_card_id'],
            $card['item_type'],
            $card['item_label'],
            $card['item_value'],
            $card['item_position'],
            $card['item_meta'],
            $card['item_created_at'],
            $card['item_updated_at']
        );

        $card['items'] = [];

        foreach ($rows as $row) {
            if ($row['item_id'] === null) continue;

            $card['items'][] = [
                'id' => (int) $row['item_id'],
                'card_id' => (int) $row['item_card_id'],
                'type' => $row['item_type'],
                'label' => $row['item_label'],
                'value' => $row['item_value'],
                'position' => (int) $row['item_position'],
                'meta' => $row['item_meta'],
                'created_at' => $row['item_created_at'],
                'updated_at' => $row['item_updated_at']
            ];
        }

        return $card;
    }
}
