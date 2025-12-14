<?php

use App\Core\Migration;

/**
* Create the analytics_events table for per-event tracking.
*
* @since 0.0.5
*/
return new class extends Migration {
    public function up(): void
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS analytics_events (
                id INT AUTO_INCREMENT PRIMARY KEY,
                card_id INT NULL,
                card_slug VARCHAR(255) NULL,
                user_id INT NULL,
                event_type VARCHAR(64) NOT NULL,
                event_name VARCHAR(64) NOT NULL,
                target VARCHAR(128) NULL,
                referrer VARCHAR(512) NULL,
                referrer_host VARCHAR(255) NULL,
                source VARCHAR(255) NULL,
                device_type VARCHAR(32) NULL,
                os VARCHAR(64) NULL,
                browser VARCHAR(64) NULL,
                user_agent VARCHAR(512) NULL,
                ip_address VARCHAR(128) NULL,
                accept_language VARCHAR(128) NULL,
                country VARCHAR(64) NULL,
                region VARCHAR(128) NULL,
                city VARCHAR(128) NULL,
                is_new_view TINYINT(1) NOT NULL DEFAULT 0,
                metadata JSON NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_analytics_card_event (card_id, event_type, created_at),
                INDEX idx_analytics_slug_created (card_slug, created_at),
                INDEX idx_analytics_event_created (event_type, created_at),
                CONSTRAINT fk_analytics_card FOREIGN KEY (card_id) REFERENCES cards(id) ON DELETE SET NULL,
                CONSTRAINT fk_analytics_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS analytics_events");
    }
};
