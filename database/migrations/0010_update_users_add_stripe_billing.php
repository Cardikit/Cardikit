<?php

use App\Core\Migration;
use App\Core\Database;

/**
* Add Stripe billing fields to users.
*
* @since 0.0.7
*/
return new class extends Migration {
    public function up(): void
    {
        $pdo = Database::connect();
        $columns = $this->existingColumns($pdo);

        if (!in_array('stripe_customer_id', $columns, true)) {
            $this->execute("ALTER TABLE users ADD COLUMN stripe_customer_id VARCHAR(255) NULL");
        }

        if (!in_array('stripe_subscription_id', $columns, true)) {
            $this->execute("ALTER TABLE users ADD COLUMN stripe_subscription_id VARCHAR(255) NULL");
        }

        if (!in_array('plan', $columns, true)) {
            $this->execute("ALTER TABLE users ADD COLUMN plan VARCHAR(100) NULL");
        }

        if (!in_array('plan_status', $columns, true)) {
            $this->execute("ALTER TABLE users ADD COLUMN plan_status VARCHAR(50) NULL");
        }

        if (!in_array('plan_ends_at', $columns, true)) {
            $this->execute("ALTER TABLE users ADD COLUMN plan_ends_at DATETIME NULL");
        }
    }

    public function down(): void
    {
        $pdo = Database::connect();
        $columns = $this->existingColumns($pdo);

        foreach (['plan_ends_at', 'plan_status', 'plan', 'stripe_subscription_id', 'stripe_customer_id'] as $column) {
            if (in_array($column, $columns, true)) {
                $this->execute("ALTER TABLE users DROP COLUMN {$column}");
            }
        }
    }

    protected function existingColumns(\PDO $pdo): array
    {
        $stmt = $pdo->query("
            SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users'
        ");
        return $stmt ? $stmt->fetchAll(\PDO::FETCH_COLUMN) : [];
    }
};
