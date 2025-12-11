export interface AnalyticsTotals {
    views: number;
    new_views: number;
    returning_views: number;
    clicks: number;
    qr_scans: number;
    nfc_scans: number;
}

export interface AnalyticsTimeseriesPoint {
    date: string;
    views: number;
    clicks: number;
}

export interface AnalyticsClickStat {
    event_name: string;
    count: number;
}

export interface AnalyticsSummaryResponse {
    totals: AnalyticsTotals;
    top_clicks: AnalyticsClickStat[];
    timeseries: AnalyticsTimeseriesPoint[];
}
