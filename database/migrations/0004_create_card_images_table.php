<?php

use App\Core\Migration;

/**
* Create the card_images table with specified columns.
* Contains up and down methods for migration commands.
*
* @since 0.0.2
*/
return new class extends Migration {
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS card_images (
                id INT AUTO_INCREMENT PRIMARY KEY,
                card_id INT NOT NULL,
                type ENUM('banner', 'avatar') NOT NULL,
                image_url VARCHAR(512) NOT NULL,
                image_path VARCHAR(512) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY unique_card_type (card_id, type),
                FOREIGN KEY (card_id) REFERENCES cards(id) ON DELETE CASCADE
            )
        ");
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS card_images");
    }
};
