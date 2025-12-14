<?php

use App\Core\Migration;
use App\Core\Database;

/**
* Add a role column to the users table.
*
* @since 0.0.3
*/
return new class extends Migration {
    public function up(): void
    {
        $pdo = Database::connect();
        $exists = $pdo->query("
            SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'users'
              AND COLUMN_NAME = 'role'
        ")->fetchColumn();

        if ($exists) {
            return;
        }

        $this->execute("
            ALTER TABLE users
            ADD COLUMN role TINYINT NOT NULL DEFAULT 0
        ");
    }

    public function down(): void
    {
        $pdo = Database::connect();
        $exists = $pdo->query("
            SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'users'
              AND COLUMN_NAME = 'role'
        ")->fetchColumn();

        if (!$exists) {
            return;
        }

        $this->execute("
            ALTER TABLE users
            DROP COLUMN role
        ");
    }
};
