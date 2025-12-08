<?php

namespace App\Models;

/**
* Category model for blog categories.
*
* @package App\Models
*
* @since 0.0.3
*/
class Category extends Model
{
    /**
    * SQL table for Category model.
    *
    * @var string
    */
    protected string $table = 'categories';

    /**
    * Fillable columns.
    *
    * @var array<int, string>
    */
    protected array $fillable = ['name', 'slug', 'description', 'parent_id'];

    /**
    * Find a category by slug.
    *
    * @param string $slug
    *
    * @return array|null
    */
    public static function findBySlug(string $slug): ?array
    {
        return (new static())->findBy('slug', $slug);
    }

    /**
    * List all categories ordered by name.
    *
    * @return array<int, array>|null
    */
    public static function allOrdered(): ?array
    {
        $instance = new static();
        $stmt = $instance->db->query("SELECT * FROM categories ORDER BY name ASC");
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $rows ?: null;
    }

    /**
    * List all categories with a count of published posts.
    *
    * @return array<int, array>|null
    */
    public static function allOrderedWithPostCounts(): ?array
    {
        $instance = new static();

        $sql = "
            SELECT
                c.*,
                (
                    SELECT COUNT(*)
                    FROM blogs b
                    WHERE b.category_id = c.id
                      AND b.status = 'published'
                ) AS post_count
            FROM categories c
            ORDER BY c.name ASC
        ";

        $stmt = $instance->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!$rows) {
            return null;
        }

        return array_map(function (array $row): array {
            $row['post_count'] = (int) $row['post_count'];

            return $row;
        }, $rows);
    }

    /**
    * List the newest categories by creation date with published post counts.
    *
    * @param int $limit
    *
    * @return array<int, array>|null
    */
    public static function latest(int $limit = 5): ?array
    {
        $instance = new static();
        $sql = "
            SELECT
                c.*,
                (
                    SELECT COUNT(*)
                    FROM blogs b
                    WHERE b.category_id = c.id
                      AND b.status = 'published'
                ) AS post_count
            FROM categories c
            ORDER BY c.created_at DESC
            LIMIT :limit
        ";

        $stmt = $instance->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!$rows) {
            return null;
        }

        return array_map(function (array $row): array {
            $row['post_count'] = (int) $row['post_count'];

            return $row;
        }, $rows);
    }

    /**
    * Find a category by id.
    *
    * @param int $id
    *
    * @return array|null
    */
    public static function findById(int $id): ?array
    {
        return (new static())->findBy('id', $id);
    }
}
