<?php

use App\Core\Migration;
use App\Core\Database;

/**
* Add a trial_used flag to users to prevent multiple free trials.
*
* @since 0.0.7
*/
return new class extends Migration {
    public function up(): void
    {
        $pdo = Database::connect();
        $exists = $pdo->query("
            SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'users'
              AND COLUMN_NAME = 'trial_used'
        ")->fetchColumn();

        if ($exists) {
            return;
        }

        $this->execute("
            ALTER TABLE users
            ADD COLUMN trial_used TINYINT(1) NOT NULL DEFAULT 0
        ");
    }

    public function down(): void
    {
        $pdo = Database::connect();
        $exists = $pdo->query("
            SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'users'
              AND COLUMN_NAME = 'trial_used'
        ")->fetchColumn();

        if (!$exists) {
            return;
        }

        $this->execute("
            ALTER TABLE users
            DROP COLUMN trial_used
        ");
    }
};
