<?php

use App\Core\Migration;

/**
* Create the cards table with specified columns.
* Contains up and down methods for migration commands.
*
* @since 0.0.1
*/
return new class extends Migration {
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS cards (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )"
        );
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS cards");
    }
};
