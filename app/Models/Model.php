<?php

namespace App\Models;

use App\Core\Database;
use PDO;

/**
* Base class for models.
*
* @package App\Models
*
* @since 0.0.1
*/
abstract class Model
{
    /**
    * The database connection.
    *
    * @var PDO
    *
    * @since 0.0.1
    */
    protected PDO $db;

    /**
    * The table name for the model.
    *
    * @var string
    *
    * @since 0.0.1
    */
    protected string $table;

    /**
    * The allowed columns for mass assignment.
    *
    * @var array<int, string>
    *
    * @since 0.0.1
    */
    protected array $fillable = [];

    /**
    * Connects to the database.
    *
    * @since 0.0.1
    */
    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connect();
    }

    /**
    * Finds the first record that
    * matches the specified column
    * and value.
    *
    * @param string $column
    * @param mixed $value
    *
    * @return array|null
    *
    * @since 0.0.1
    */
    public function findBy(string $column, mixed $value): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1");
        $stmt->execute(['value' => $value]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /**
    * Finds all records
    * that match the specicified column
    * and value.
    *
    * @param string $column
    * @param mixed $value
    *
    * @return array|null
    *
    * @since 0.0.1
    */
    public function findAllBy(string $column, mixed $value): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = :value");
        $stmt->execute(['value' => $value]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /**
    * Creates a new record using fillable columns.
    *
    * @param array $data
    *
    * @return bool
    *
    * @since 0.0.1
    */
    public function create(array $data): bool
    {
        $payload = $this->beforeCreate($this->filterFillable($data));

        if (empty($payload)) {
            return false;
        }

        $columns = array_keys($payload);
        $placeholders = array_map(fn ($column) => ":{$column}", $columns);
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $stmt = $this->db->prepare($sql);

        return $stmt->execute($payload);
    }

    /**
    * Updates a record by id.
    *
    * @param int $id
    * @param array $data
    *
    * @return bool
    *
    * @since 0.0.1
    */
    public function updateById(int $id, array $data): bool
    {
        $payload = $this->beforeUpdate($this->filterFillable($data));

        if (empty($payload)) {
            return false;
        }

        $columns = array_keys($payload);
        $assignments = array_map(fn ($column) => "{$column} = :{$column}", $columns);
        $sql = sprintf(
            'UPDATE %s SET %s WHERE id = :id',
            $this->table,
            implode(', ', $assignments)
        );

        $payload['id'] = $id;

        $stmt = $this->db->prepare($sql);

        return $stmt->execute($payload);
    }

    /**
    * Deletes a record by id.
    *
    * @param int $id
    *
    * @return bool
    *
    * @since 0.0.1
    */
    public function deleteById(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");

        return $stmt->execute(['id' => $id]);
    }

    /**
    * Hook that runs before create statements.
    *
    * @param array $data
    *
    * @return array
    *
    * @since 0.0.1
    */
    protected function beforeCreate(array $data): array
    {
        return $data;
    }

    /**
    * Hook that runs before update statements.
    *
    * @param array $data
    *
    * @return array
    *
    * @since 0.0.1
    */
    protected function beforeUpdate(array $data): array
    {
        return $data;
    }

    /**
    * Filters data by the fillable whitelist preserving order.
    *
    * @param array $data
    *
    * @return array
    *
    * @since 0.0.1
    */
    protected function filterFillable(array $data): array
    {
        $filtered = [];

        foreach ($this->fillable as $column) {
            if (array_key_exists($column, $data)) {
                $filtered[$column] = $data[$column];
            }
        }

        return $filtered;
    }
}
