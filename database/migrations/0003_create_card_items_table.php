<?php

use App\Core\Migration;

/**
* Create the card_items table with specified columns.
* Contains up and down methods for migration commands.
*
* @since 0.0.1
*/
return new class extends Migration {
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS card_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                card_id INT NOT NULL,
                type VARCHAR(255) NOT NULL,
                label VARCHAR(255) DEFAULT NULL,
                value VARCHAR(255) NOT NULL,
                position INT DEFAULT 0,
                meta JSON DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_card_items_card
                    FOREIGN KEY (card_id) REFERENCES cards(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS card_items");
    }
};

