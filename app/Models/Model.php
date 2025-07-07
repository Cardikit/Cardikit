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
    * Connects to the database.
    *
    * @since 0.0.1
    */
    public function __construct()
    {
        $this->db = Database::connect();
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
}
