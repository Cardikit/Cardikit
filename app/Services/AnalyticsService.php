<?php

namespace App\Services;

use App\Core\Request;
use App\Models\AnalyticsEvent;
use App\Models\Card;
use App\Models\User;
use App\Services\AuthService;
use App\Core\Database;

/**
* Handles recording analytics events with light sanitization and enrichment.
*
* @package App\Services
*
* @since 0.0.5
*/
class AnalyticsService
{
    private const PRO_ROLE_THRESHOLD = 2;

    /**
    * Record a single analytics event row.
    *
    * @param array $payload
    * @param Request $request
    *
    * @return array{status:int,body:array}
    */
    public function recordEvent(array $payload, Request $request): array
    {
        $eventType = $this->cleanString($payload['event_type'] ?? null, 64);
        $eventName = $this->cleanString($payload['event_name'] ?? null, 64);

        if ($eventType === null || $eventName === null) {
            return [
                'status' => 422,
                'body' => ['message' => 'event_type and event_name are required'],
            ];
        }

        $headers = array_change_key_case($request->headers(), CASE_LOWER);

        $cardId = $this->normalizeInt($payload['card_id'] ?? null);
        $cardSlug = $this->cleanString($payload['card_slug'] ?? ($payload['slug'] ?? null), 255);
        $resolvedCard = null;

        if ($cardId === null && $cardSlug !== null) {
            $resolvedCard = (new Card())->findBy('slug', $cardSlug);
            if ($resolvedCard) {
                $cardId = (int) $resolvedCard['id'];
            }
        }

        if ($resolvedCard === null && $cardId !== null) {
            $resolvedCard = (new Card())->findBy('id', $cardId);
        }

        $ownerUserId = $this->normalizeInt($resolvedCard['user_id'] ?? null);
        $isProOwner = $this->isProUser($ownerUserId);

        if ($resolvedCard !== null && !$isProOwner) {
            return [
                'status' => 200,
                'body' => [
                    'message' => 'Analytics not collected for free plan.',
                    'recorded' => false,
                ],
            ];
        }

        $target = $this->cleanString($payload['target'] ?? ($payload['button'] ?? null), 128);
        $referrer = $this->cleanString($payload['referrer'] ?? ($_SERVER['HTTP_REFERER'] ?? null), 512);
        $referrerHost = $this->cleanString($payload['referrer_host'] ?? (parse_url((string) $referrer, PHP_URL_HOST) ?: null), 255);
        $source = $this->cleanString($payload['source'] ?? $referrerHost, 255);
        $userAgent = $this->cleanString($payload['user_agent'] ?? ($headers['user-agent'] ?? null), 512);
        $parsedUa = $this->parseUserAgent($userAgent);
        $deviceType = $parsedUa['device'] ?? null;
        $ipAddressRaw = $this->cleanString($request->ip(), 45);
        $ipHash = $ipAddressRaw ? $this->hashIp($ipAddressRaw) : null;
        $acceptLanguage = $this->cleanString($headers['accept-language'] ?? null, 128);

        $location = is_array($payload['location'] ?? null) ? $payload['location'] : [];
        $country = $this->cleanString($location['country'] ?? null, 64);
        $region = $this->cleanString($location['region'] ?? null, 128);
        $city = $this->cleanString($location['city'] ?? null, 128);

        if ($country === null && $ipHash !== null) {
            [$country, $region, $city] = $this->resolveLocation($ipHash, $ipAddressRaw, $country, $region, $city);
        }

        $isNewView = $this->normalizeBool($payload['is_new_view'] ?? $payload['new_view'] ?? false);

        $metadataPayload = $this->buildMetadata($payload, $request);
        $metadata = null;
        if ($metadataPayload !== null) {
            $encoded = json_encode($metadataPayload, JSON_UNESCAPED_SLASHES);
            $metadata = $encoded !== false ? $encoded : null;
        }

        $userId = $this->normalizeInt($payload['user_id'] ?? ($resolvedCard['user_id'] ?? null));
        if ($userId === null) {
            $userId = (new AuthService())->currentUserId();
        }

        $record = [
            'card_id' => $cardId,
            'card_slug' => $cardSlug,
            'user_id' => $userId,
            'event_type' => $eventType,
            'event_name' => $eventName,
            'target' => $target,
            'referrer' => $referrer,
            'referrer_host' => $referrerHost,
            'source' => $source,
            'device_type' => $deviceType,
            'os' => $parsedUa['os'] ?? null,
            'browser' => $parsedUa['browser'] ?? null,
            'ip_address' => $ipHash,
            'accept_language' => $acceptLanguage,
            'country' => $country,
            'region' => $region,
            'city' => $city,
            'is_new_view' => $isNewView ? 1 : 0,
            'metadata' => $metadata,
        ];

        try {
            $created = (new AnalyticsEvent())->create($record);
            if (!$created) {
                return [
                    'status' => 500,
                    'body' => ['message' => 'Event could not be recorded'],
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status' => 500,
                'body' => [
                    'message' => 'Event could not be recorded',
                    'error' => $e->getMessage(),
                ],
            ];
        }

        return [
            'status' => 201,
            'body' => ['message' => 'Event recorded'],
        ];
    }

    /**
    * Determine if a user is Pro based on their role.
    *
    * @param int|null $userId
    *
    * @return bool
    */
    protected function isProUser(?int $userId): bool
    {
        if ($userId === null) {
            return false;
        }

        $user = User::findById($userId);
        $role = isset($user['role']) ? (int) $user['role'] : 0;

        return $role >= self::PRO_ROLE_THRESHOLD;
    }

    /**
    * Trim and bound a string.
    */
    protected function cleanString(mixed $value, int $maxLength): ?string
    {
        if ($value === null) {
            return null;
        }

        $string = trim((string) $value);
        if ($string === '') {
            return null;
        }

        if (strlen($string) > $maxLength) {
            return substr($string, 0, $maxLength);
        }

        return $string;
    }

    /**
    * Normalize int-ish values.
    */
    protected function normalizeInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $filtered = filter_var($value, FILTER_VALIDATE_INT);

        return $filtered === false ? null : (int) $filtered;
    }

    /**
    * Normalize truthy/falsy payload values.
    */
    protected function normalizeBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $truthy = ['1', 1, 'true', 'yes', 'on'];
        $falsy = ['0', 0, 'false', 'no', 'off', null, ''];

        if (in_array($value, $truthy, true)) {
            return true;
        }

        if (in_array($value, $falsy, true)) {
            return false;
        }

        return false;
    }

    /**
    * Simple device classification.
    */
    protected function detectDeviceType(?string $userAgent): ?string
    {
        if ($userAgent === null) {
            return null;
        }

        $ua = strtolower($userAgent);

        if (str_contains($ua, 'ipad') || str_contains($ua, 'tablet')) {
            return 'tablet';
        }

        if (str_contains($ua, 'mobi')) {
            return 'mobile';
        }

        if (str_contains($ua, 'android') && !str_contains($ua, 'mobile')) {
            return 'tablet';
        }

        if (str_contains($ua, 'bot') || str_contains($ua, 'crawl') || str_contains($ua, 'spider')) {
            return 'bot';
        }

        return 'desktop';
    }

    /**
    * Parse user agent into browser/os/device buckets.
    */
    protected function parseUserAgent(?string $userAgent): array
    {
        if ($userAgent === null) {
            return ['browser' => null, 'os' => null, 'device' => null];
        }

        $ua = strtolower($userAgent);

        $browser = null;
        if (str_contains($ua, 'edg/')) {
            $browser = 'Edge';
        } elseif (str_contains($ua, 'chrome/')) {
            $browser = 'Chrome';
        } elseif (str_contains($ua, 'safari/') && !str_contains($ua, 'chrome/')) {
            $browser = 'Safari';
        } elseif (str_contains($ua, 'firefox/')) {
            $browser = 'Firefox';
        } elseif (str_contains($ua, 'opera') || str_contains($ua, 'opr/')) {
            $browser = 'Opera';
        }

        $os = null;
        if (str_contains($ua, 'windows nt')) {
            $os = 'Windows';
        } elseif (str_contains($ua, 'mac os x')) {
            $os = 'macOS';
        } elseif (str_contains($ua, 'android')) {
            $os = 'Android';
        } elseif (str_contains($ua, 'iphone') || str_contains($ua, 'ipad') || str_contains($ua, 'ipod')) {
            $os = 'iOS';
        } elseif (str_contains($ua, 'linux')) {
            $os = 'Linux';
        }

        $device = $this->detectDeviceType($userAgent);

        return [
            'browser' => $browser,
            'os' => $os,
            'device' => $device,
        ];
    }

    /**
    * Build optional metadata blob with a small whitelist.
    */
    protected function buildMetadata(array $payload, Request $request): ?array
    {
        $meta = [];

        $stringKeys = [
            'client_time',
            'session_id',
            'visitor_id',
            'card_theme',
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
        ];

        foreach ($stringKeys as $key) {
            if (isset($payload[$key])) {
                $value = $this->cleanString($payload[$key], 200);
                if ($value !== null) {
                    $meta[$key] = $value;
                }
            }
        }

        if (!empty($payload['meta']) && is_array($payload['meta'])) {
            $meta['meta'] = $this->sanitizeMap($payload['meta'], 20, 200);
        } elseif (!empty($payload['metadata']) && is_array($payload['metadata'])) {
            $meta['meta'] = $this->sanitizeMap($payload['metadata'], 20, 200);
        }

        $path = $this->cleanString($request->uri(), 255);
        if ($path !== null) {
            $meta['path'] = $path;
        }

        $query = $request->query();
        if (!empty($query)) {
            $meta['query'] = $this->sanitizeMap($query, 20, 200);
        }

        return empty($meta) ? null : $meta;
    }

    /**
    * Sanitize an associative array, limiting keys and value length.
    */
    protected function sanitizeMap(array $input, int $maxKeys, int $valueLength): array
    {
        $clean = [];
        $count = 0;

        foreach ($input as $key => $value) {
            if ($count >= $maxKeys) {
                break;
            }

            $cleanKey = $this->cleanString((string) $key, 64);
            if ($cleanKey === null) {
                continue;
            }

            if (is_array($value) || is_object($value)) {
                continue;
            }

            $clean[$cleanKey] = $this->cleanString((string) $value, $valueLength);
            $count++;
        }

        return $clean;
    }

    /**
    * Hash IP for storage (no salts to allow reuse lookups).
    */
    protected function hashIp(string $ip): string
    {
        return hash('sha256', $ip);
    }

    /**
    * Resolve geolocation using existing records first, then ipapi.co with caching.
    */
    protected function resolveLocation(string $ipHash, ?string $rawIp, ?string $country, ?string $region, ?string $city): array
    {
        static $memoryCache = [];

        // In-memory cache per request
        if (isset($memoryCache[$ipHash])) {
            $cached = $memoryCache[$ipHash];
            return [$cached['country'], $cached['region'], $cached['city']];
        }

        // DB cache
        $existing = (new AnalyticsEvent())->findLocationByIp($ipHash);
        if ($existing) {
            $country = $country ?? $this->cleanString($existing['country'] ?? null, 64);
            $region = $region ?? $this->cleanString($existing['region'] ?? null, 128);
            $city = $city ?? $this->cleanString($existing['city'] ?? null, 128);
            $memoryCache[$ipHash] = ['country' => $country, 'region' => $region, 'city' => $city];
            return [$country, $region, $city];
        }

        if ($rawIp === null) {
            return [$country, $region, $city];
        }

        $lookup = $this->fetchIpApi($rawIp);
        if ($lookup !== null) {
            $country = $country ?? $this->cleanString($lookup['country'] ?? null, 64);
            $region = $region ?? $this->cleanString($lookup['region'] ?? null, 128);
            $city = $city ?? $this->cleanString($lookup['city'] ?? null, 128);
            $memoryCache[$ipHash] = ['country' => $country, 'region' => $region, 'city' => $city];
        }

        return [$country, $region, $city];
    }

    /**
    * Call ipapi.co for location; safe-guards around failures.
    */
    protected function fetchIpApi(string $ip): ?array
    {
        $url = "https://ipapi.co/{$ip}/json/";
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 1.5,
                'ignore_errors' => true,
                'header' => "User-Agent: Cardikit-Analytics\r\n",
            ]
        ]);

        try {
            $response = @file_get_contents($url, false, $context);
            if ($response === false) {
                return null;
            }
            $data = json_decode($response, true);
            if (!is_array($data)) {
                return null;
            }

            return [
                'country' => $data['country_name'] ?? null,
                'region' => $data['region'] ?? $data['region_code'] ?? null,
                'city' => $data['city'] ?? null,
            ];
        } catch (\Throwable) {
            return null;
        }
    }
}
