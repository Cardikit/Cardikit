<?php

namespace App\Services;

use App\Core\Database;
use PDO;

/**
* Provides aggregated analytics summaries for dashboard use.
*
* @since 0.0.5
*/
class AnalyticsReportService
{
    /**
    * Build a summary for a user's cards.
    *
    * @param int $userId
    * @param int $days
    *
    * @return array
    */
    public function summaryForUser(int $userId, ?int $days = 30, ?int $cardId = null): array
    {
        $days = $days === null ? null : max(1, min($days, 365));
        $pdo = Database::connect();

        $cardIds = $this->userCardIds($pdo, $userId);
        if ($cardId !== null) {
            $cardIds = array_values(array_filter($cardIds, fn ($id) => $id === $cardId));
        }
        if (empty($cardIds)) {
            return $this->emptySummary();
        }

        $placeholders = $this->placeholders('card', count($cardIds));
        $params = array_combine($placeholders, $cardIds);
        if ($days !== null) {
            $params['days'] = $days;
        }

        $totals = $this->fetchTotals($pdo, $placeholders, $params, $days);
        $topClicks = $this->fetchTopClicks($pdo, $placeholders, $params, $days);
        $timeseries = $this->fetchTimeseries($pdo, $placeholders, $params, $days);

        return [
            'totals' => $totals,
            'top_clicks' => $topClicks,
            'timeseries' => $timeseries,
        ];
    }

    protected function fetchTotals(PDO $pdo, array $ph, array $params, ?int $days): array
    {
        $dateClause = $days === null ? "" : "AND created_at >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL :days DAY)";

        $sql = "
            SELECT
                SUM(CASE WHEN event_type = 'view' THEN 1 ELSE 0 END) AS views,
                SUM(CASE WHEN event_type = 'view' AND is_new_view = 1 THEN 1 ELSE 0 END) AS new_views,
                SUM(CASE WHEN event_type = 'view' AND is_new_view = 0 THEN 1 ELSE 0 END) AS returning_views,
                SUM(CASE WHEN event_type = 'click' THEN 1 ELSE 0 END) AS clicks,
                SUM(CASE WHEN event_name = 'qr_scan' THEN 1 ELSE 0 END) AS qr_scans,
                SUM(CASE WHEN event_name = 'nfc_scan' THEN 1 ELSE 0 END) AS nfc_scans
            FROM analytics_events
            WHERE card_id IN (" . implode(',', array_map(fn($p) => ':' . $p, $ph)) . ")
              {$dateClause}
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'views' => (int) ($row['views'] ?? 0),
            'new_views' => (int) ($row['new_views'] ?? 0),
            'returning_views' => (int) ($row['returning_views'] ?? 0),
            'clicks' => (int) ($row['clicks'] ?? 0),
            'qr_scans' => (int) ($row['qr_scans'] ?? 0),
            'nfc_scans' => (int) ($row['nfc_scans'] ?? 0),
        ];
    }

    protected function fetchTopClicks(PDO $pdo, array $ph, array $params, ?int $days): array
    {
        $dateClause = $days === null ? "" : "AND created_at >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL :days DAY)";

        $sql = "
            SELECT event_name, COUNT(*) AS count
            FROM analytics_events
            WHERE card_id IN (" . implode(',', array_map(fn($p) => ':' . $p, $ph)) . ")
              AND event_type = 'click'
              {$dateClause}
            GROUP BY event_name
            ORDER BY count DESC
            LIMIT 10
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return array_map(fn ($row) => [
            'event_name' => (string) ($row['event_name'] ?? 'unknown'),
            'count' => (int) ($row['count'] ?? 0),
        ], $rows);
    }

    protected function fetchTimeseries(PDO $pdo, array $ph, array $params, ?int $days): array
    {
        $dateClause = $days === null ? "" : "AND created_at >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL :days DAY)";

        $sql = "
            SELECT
                DATE(created_at) AS date,
                SUM(CASE WHEN event_type = 'view' THEN 1 ELSE 0 END) AS views,
                SUM(CASE WHEN event_type = 'click' THEN 1 ELSE 0 END) AS clicks
            FROM analytics_events
            WHERE card_id IN (" . implode(',', array_map(fn($p) => ':' . $p, $ph)) . ")
              {$dateClause}
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at) ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return array_map(fn ($row) => [
            'date' => (string) ($row['date'] ?? ''),
            'views' => (int) ($row['views'] ?? 0),
            'clicks' => (int) ($row['clicks'] ?? 0),
        ], $rows);
    }

    /**
    * Fetch card IDs owned by the user.
    *
    * @return array<int>
    */
    protected function userCardIds(PDO $pdo, int $userId): array
    {
        $stmt = $pdo->prepare("SELECT id FROM cards WHERE user_id = :user");
        $stmt->execute(['user' => $userId]);
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];

        return array_map('intval', $ids);
    }

    protected function placeholders(string $prefix, int $count): array
    {
        $out = [];
        for ($i = 0; $i < $count; $i++) {
            $out[] = "{$prefix}{$i}";
        }
        return $out;
    }

    protected function emptySummary(): array
    {
        return [
            'totals' => [
                'views' => 0,
                'new_views' => 0,
                'returning_views' => 0,
                'clicks' => 0,
                'qr_scans' => 0,
                'nfc_scans' => 0,
            ],
            'top_clicks' => [],
            'timeseries' => [],
        ];
    }
}
