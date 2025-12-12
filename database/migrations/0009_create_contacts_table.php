<?php

use App\Core\Migration;

/**
* Create the contacts table for sharing contact information.
*
* @since 0.0.5
*/
return new class extends Migration {
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS contacts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                card_id INT NULL,
                card_slug VARCHAR(255) NULL,
                name VARCHAR(255) NULL,
                email VARCHAR(255) NULL,
                phone VARCHAR(64) NULL,
                source_url VARCHAR(512) NULL,
                user_agent VARCHAR(512) NULL,
                metadata JSON NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_contacts_card_created (card_id, created_at),
                INDEX idx_contacts_slug_created (card_slug, created_at),
                INDEX idx_contacts_email (email),
                CONSTRAINT fk_contacts_card FOREIGN KEY (card_id) REFERENCES cards(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS contacts");
    }
};
