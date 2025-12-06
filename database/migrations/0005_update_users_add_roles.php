<?php

use App\Core\Migration;

/**
* Add a roles column to the users table.
*
* @since 0.0.3
*/
return new class extends Migration {
    public function up(): void
    {
        $this->execute("
            ALTER TABLE users
            ADD COLUMN role INTEGER NOT NULL DEFAULT 0
        ");
    }

    public function down(): void
    {
        //
    }
};
