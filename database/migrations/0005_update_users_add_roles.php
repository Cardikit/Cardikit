<?php

use App\Core\Migration;

/**
* Add a role column to the users table.
*
* @since 0.0.3
*/
return new class extends Migration {
    public function up(): void
    {
        $this->execute("
            ALTER TABLE users
            ADD COLUMN role TINYINT NOT NULL DEFAULT 0
        ");
    }

    public function down(): void
    {
        $this->execute("
            ALTER TABLE users
            DROP COLUMN role
        ");
    }
};
