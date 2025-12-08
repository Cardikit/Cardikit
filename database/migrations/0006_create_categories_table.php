<?php

use App\Core\Migration;

/**
* Create categories table.
*
* @since 0.0.3
*/
return new class extends Migration {
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                slug VARCHAR(255) NOT NULL UNIQUE,
                description TEXT DEFAULT NULL,
                parent_id INT DEFAULT NULL,
                image VARCHAR(255) DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_categories_parent_id (parent_id),
                CONSTRAINT fk_categories_parent
                    FOREIGN KEY (parent_id) REFERENCES categories(id)
                    ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS categories");
    }
};
