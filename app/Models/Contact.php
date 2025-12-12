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

    /**
    * Paginate contacts for a user with optional card filter.
    *
    * @return array{data:array,total:int,page:int,per_page:int}
    */
    public function paginateForUser(int $userId, ?int $cardId, int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;

        $where = [];
        $params = ['user_id' => $userId];

        if ($cardId !== null) {
            $where[] = '((c.card_id = :card_id) OR (c.card_slug = (SELECT slug FROM cards WHERE id = :card_id LIMIT 1)))';
            $params['card_id'] = $cardId;
        }

        $whereSql = '';
        if (!empty($where)) {
            $whereSql = 'AND ' . implode(' AND ', $where);
        }

        $dataSql = "
            SELECT c.*, COALESCE(ci.name, cs.name) AS card_name
            FROM contacts c
            LEFT JOIN cards ci ON ci.id = c.card_id
            LEFT JOIN cards cs ON cs.slug = c.card_slug
            WHERE (ci.user_id = :user_id OR cs.user_id = :user_id)
            {$whereSql}
            ORDER BY c.created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $countSql = "
            SELECT COUNT(*) AS count
            FROM contacts c
            LEFT JOIN cards ci ON ci.id = c.card_id
            LEFT JOIN cards cs ON cs.slug = c.card_slug
            WHERE (ci.user_id = :user_id OR cs.user_id = :user_id)
            {$whereSql}
        ";

        $stmt = $this->db->prepare($dataSql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key === 'card_id' ? ':card_id' : ':' . $key, $value, \PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];

        $countStmt = $this->db->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key === 'card_id' ? ':card_id' : ':' . $key, $value, \PDO::PARAM_INT);
        }
        $countStmt->execute();
        $total = (int) ($countStmt->fetchColumn() ?: 0);

        return [
            'data' => $rows,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
        ];
    }

    /**
    * Fetch all contacts for CSV export with a sane cap.
    *
    * @return array<int,array<string,mixed>>
    */
    public function allForUser(int $userId, ?int $cardId, int $limit = 2000): array
    {
        $where = [];
        $params = ['user_id' => $userId];

        if ($cardId !== null) {
            $where[] = '((c.card_id = :card_id) OR (c.card_slug = (SELECT slug FROM cards WHERE id = :card_id LIMIT 1)))';
            $params['card_id'] = $cardId;
        }

        $whereSql = '';
        if (!empty($where)) {
            $whereSql = 'AND ' . implode(' AND ', $where);
        }

        $sql = "
            SELECT c.*, COALESCE(ci.name, cs.name) AS card_name
            FROM contacts c
            LEFT JOIN cards ci ON ci.id = c.card_id
            LEFT JOIN cards cs ON cs.slug = c.card_slug
            WHERE (ci.user_id = :user_id OR cs.user_id = :user_id)
            {$whereSql}
            ORDER BY c.created_at DESC
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key === 'card_id' ? ':card_id' : ':' . $key, $value, \PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }
}
