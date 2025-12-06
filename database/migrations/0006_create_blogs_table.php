<?php

use App\Core\Migration;

/**
* Create the blogs table with specified columns.
* Contains up and down methods for migration commands.
*
* @since 0.0.3
*/
return new class extends Migration {
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS blogs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                slug VARCHAR(255) NOT NULL UNIQUE,
                excerpt TEXT DEFAULT NULL,
                content LONGTEXT NOT NULL,
                cover_image_url VARCHAR(512) DEFAULT NULL,
                status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
                published_at DATETIME DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_blogs_user_id (user_id),
                INDEX idx_blogs_status_published (status, published_at),
                CONSTRAINT fk_blogs_user
                    FOREIGN KEY (user_id) REFERENCES users(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        "
        );
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS blogs");
    }
};
