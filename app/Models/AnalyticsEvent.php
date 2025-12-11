<?php

namespace App\Models;

/**
* AnalyticsEvent model for per-event telemetry storage.
*
* @package App\Models
*
* @since 0.0.5
*/
class AnalyticsEvent extends Model
{
    /**
    * SQL table for AnalyticsEvent model.
    *
    * @var string
    */
    protected string $table = 'analytics_events';

    /**
    * Mass-assignable columns.
    *
    * @var array<int, string>
    */
    protected array $fillable = [
        'card_id',
        'card_slug',
        'user_id',
        'event_type',
        'event_name',
        'target',
        'referrer',
        'referrer_host',
        'source',
        'device_type',
        'os',
        'browser',
        'ip_address',
        'accept_language',
        'country',
        'region',
        'city',
        'is_new_view',
        'metadata',
    ];

    /**
    * Return a single location row by hashed IP if present.
    */
    public function findLocationByIp(string $ipHash): ?array
    {
        $stmt = $this->db->prepare("
            SELECT country, region, city
            FROM {$this->table}
            WHERE ip_address = :ip
              AND country IS NOT NULL
            ORDER BY id DESC
            LIMIT 1
        ");
        $stmt->execute(['ip' => $ipHash]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ?: null;
    }
}
