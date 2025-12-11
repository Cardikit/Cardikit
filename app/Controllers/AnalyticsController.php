<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\AnalyticsService;
use App\Services\AnalyticsReportService;
use App\Services\AuthService;

/**
* Handles analytics event ingestion.
*
* @package App\Controllers
*
* @since 0.0.5
*/
class AnalyticsController
{
    /**
    * Store a single analytics event row.
    *
    * @param Request $request
    *
    * @return void
    */
    public function track(Request $request): void
    {
        $result = (new AnalyticsService())->recordEvent($request->body(), $request);

        Response::json($result['body'], $result['status']);
    }

    /**
    * Return aggregated analytics for the authenticated user.
    */
    public function summary(Request $request): void
    {
        $userId = (new AuthService())->currentUserId();
        if (!$userId) {
            Response::json(['message' => 'Unauthorized'], 401);
            return;
        }

        $query = $request->query();
        $days = array_key_exists('days', $query) ? (int) $query['days'] : null;
        $cardId = isset($query['card_id']) ? (int) $query['card_id'] : null;

        $report = (new AnalyticsReportService())->summaryForUser($userId, $days, $cardId);

        Response::json($report);
    }
}
