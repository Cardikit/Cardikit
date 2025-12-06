<?php

namespace App\Models;

use App\Core\Database;

/**
* Blog model to interact with the blogs table.
*
* @package App\Models
*
* @since 0.0.3
*/
class Blog extends Model
{
    /**
    * SQL table for Blog model.
    *
    * @var string
    */
    protected string $table = 'blogs';

    /**
    * Fillable columns.
    *
    * @var array<int, string>
    */
    protected array $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image_url',
        'status',
        'published_at',
    ];

    /**
    * List published posts with optional category filter.
    *
    * @param int|null $categoryId
    * @param int $limit
    * @param int $offset
    *
    * @return array<int, array>|null
    */
    public function listPublished(?int $categoryId = null, int $limit = 20, int $offset = 0): ?array
    {
        $sql = "
            SELECT b.*, c.name AS category_name, c.slug AS category_slug
            FROM blogs b
            LEFT JOIN categories c ON c.id = b.category_id
            WHERE b.status = 'published'
        ";

        $params = [];

        if ($categoryId !== null) {
            $sql .= " AND b.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        $sql .= " ORDER BY COALESCE(b.published_at, b.created_at) DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, \PDO::PARAM_INT);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $rows ?: null;
    }

    /**
    * Find a published post by category slug and blog slug.
    *
    * @param string $categorySlug
    * @param string $slug
    *
    * @return array|null
    */
    public function findPublishedByCategoryAndSlug(string $categorySlug, string $slug): ?array
    {
        $sql = "
            SELECT b.*, c.name AS category_name, c.slug AS category_slug
            FROM blogs b
            LEFT JOIN categories c ON c.id = b.category_id
            WHERE b.slug = :slug
              AND c.slug = :category_slug
              AND b.status = 'published'
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'slug' => $slug,
            'category_slug' => $categorySlug,
        ]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
    * Find a blog post with category by id (any status).
    *
    * @param int $id
    *
    * @return array|null
    */
    public function findWithCategoryById(int $id): ?array
    {
        $sql = "
            SELECT b.*, c.name AS category_name, c.slug AS category_slug
            FROM blogs b
            LEFT JOIN categories c ON c.id = b.category_id
            WHERE b.id = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
    * Generate a URL friendly slug.
    *
    * @param string $value
    *
    * @return string
    */
    public static function slugify(string $value): string
    {
        $slug = strtolower(trim($value));
        $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug ?? '') ?? '';
        $slug = trim($slug, '-');

        return $slug !== '' ? $slug : bin2hex(random_bytes(4));
    }

    /**
    * Generate a unique slug for blogs.
    *
    * @param string $base
    * @param int|null $ignoreId
    *
    * @return string
    */
    public function generateUniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = self::slugify($base);
        $candidate = $slug;
        $attempt = 1;

        while ($this->slugExists($candidate, $ignoreId)) {
            $attempt++;
            $candidate = $slug . '-' . $attempt;

            if ($attempt > 100) {
                $candidate = $slug . '-' . bin2hex(random_bytes(2));
                break;
            }
        }

        return $candidate;
    }

    /**
    * Check if a slug already exists (optionally excluding a given id).
    *
    * @param string $slug
    * @param int|null $ignoreId
    *
    * @return bool
    */
    protected function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        $sql = "SELECT id FROM blogs WHERE slug = :slug";
        $params = ['slug' => $slug];

        if ($ignoreId !== null) {
            $sql .= " AND id != :ignore_id";
            $params['ignore_id'] = $ignoreId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (bool) $stmt->fetchColumn();
    }
}
