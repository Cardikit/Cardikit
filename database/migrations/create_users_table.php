<?php

use App\Core\Migration;

return new class extends Migration {
    public function up(): void
    {
        $this->execute("
            CREATE TABLE users (
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
        $this->execute("DROP TABLE users");
    }
};
