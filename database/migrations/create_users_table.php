<?php

use App\Core\Migration;

/**
* Create the users table with specified columns.
* Contains up and down methods for migration commands.
*
* @since 0.0.1
*/
return new class extends Migration {
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                email VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )"
        );
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS users");
    }
};
