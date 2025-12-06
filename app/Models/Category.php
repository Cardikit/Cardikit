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
