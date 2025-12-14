<?php

namespace App\Services;

use App\Core\Request;
use App\Models\Card;
use App\Models\Contact;
use App\Models\User;
use App\Services\AuthService;

/**
* Handles storing shared contact details from public card viewers.
*
* @package App\Services
*
* @since 0.0.5
*/
class ContactService
{
    private const PRO_ROLE_THRESHOLD = 2;

    /**
    * Persist a contact submission for a card.
    *
    * @param array $payload
    * @param Request $request
    *
    * @return array{status:int,body:array}
    */
    public function store(array $payload, Request $request): array
    {
        $name = $this->cleanString($payload['name'] ?? null, 255);
        $emailRaw = $payload['email'] ?? null;
        $email = $this->validateEmail($emailRaw);
        $phone = $this->cleanPhone($payload['phone'] ?? null);

        if ($email === null && $emailRaw !== null && trim((string) $emailRaw) !== '' && $this->cleanString($emailRaw, 255) !== null) {
            return [
                'status' => 422,
                'body' => ['message' => 'Please enter a valid email address.'],
            ];
        }

        if ($name === null && $email === null && $phone === null) {
            return [
                'status' => 422,
                'body' => ['message' => 'Please provide at least one contact field (name, email, or phone).'],
            ];
        }

        $cardId = $this->normalizeInt($payload['card_id'] ?? null);
        $cardSlug = $this->cleanString($payload['card_slug'] ?? ($payload['slug'] ?? null), 255);
        $card = null;

        if ($cardId === null && $cardSlug !== null) {
            $card = (new Card())->findBy('slug', $cardSlug);
            if ($card) {
                $cardId = (int) $card['id'];
            }
        } elseif ($card === null && $cardId !== null) {
            $card = (new Card())->findBy('id', $cardId);
        }

        $headers = array_change_key_case($request->headers(), CASE_LOWER);

        $sourceUrl = $this->cleanString($payload['source_url'] ?? $headers['referer'] ?? null, 512);
        $userAgent = $this->cleanString($headers['user-agent'] ?? null, 512);

        $metadata = [
            'path' => $this->cleanString($payload['path'] ?? $request->uri(), 255),
            'card_name' => $this->cleanString($payload['card_name'] ?? ($card['name'] ?? null), 255),
            'raw_phone' => $payload['phone'] ?? null,
        ];

        $ownerUserId = $this->normalizeInt($card['user_id'] ?? null);
        $isProOwner = $this->isProUser($ownerUserId);
        if ($card !== null && !$isProOwner) {
            return [
                'status' => 200,
                'body' => ['message' => 'Contacts not collected for free plan.', 'stored' => false],
            ];
        }

        $existing = (new Contact())->findDuplicate($cardId, $cardSlug, $name, $email, $phone);
        if ($existing) {
            return [
                'status' => 200,
                'body' => ['message' => 'We already have these details for this card.', 'stored' => false],
            ];
        }

        $record = [
            'card_id' => $cardId,
            'card_slug' => $cardSlug,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'source_url' => $sourceUrl,
            'user_agent' => $userAgent,
            'metadata' => json_encode($metadata),
        ];

        try {
            $created = (new Contact())->create($record);
            if (!$created) {
                return [
                    'status' => 500,
                    'body' => ['message' => 'Contact could not be saved.', 'stored' => false],
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status' => 500,
                'body' => [
                    'message' => 'Contact could not be saved.',
                    'error' => $e->getMessage(),
                    'stored' => false,
                ],
            ];
        }

        return [
            'status' => 201,
            'body' => ['message' => 'Contact saved.', 'stored' => true],
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

    protected function validateEmail(mixed $value): ?string
    {
        $string = $this->cleanString($value, 255);
        if ($string === null) {
            return null;
        }

        return filter_var($string, FILTER_VALIDATE_EMAIL) ? $string : null;
    }

    protected function cleanPhone(mixed $value): ?string
    {
        $string = $this->cleanString($value, 64);
        if ($string === null) {
            return null;
        }

        $digits = preg_replace('/[^0-9+]/', '', $string);
        return $digits !== '' ? $digits : null;
    }

    protected function normalizeInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $filtered = filter_var($value, FILTER_VALIDATE_INT);
        return $filtered === false ? null : (int) $filtered;
    }

    /**
    * List contacts for the authenticated user with optional card filter.
    *
    * @param array $query
    * @return array{status:int,body:array}
    */
    public function listForCurrentUser(array $query): array
    {
        $userId = (new AuthService())->currentUserId();
        if (!$userId) {
            return ['status' => 401, 'body' => ['message' => 'Unauthorized']];
        }

        $page = max(1, (int) ($query['page'] ?? 1));
        $perPage = 30;
        $cardId = $this->normalizeInt($query['card_id'] ?? null);

        $contactModel = new Contact();
        $result = $contactModel->paginateForUser($userId, $cardId, $page, $perPage);

        return [
            'status' => 200,
            'body' => $result,
        ];
    }

    /**
    * Export contacts as CSV for authenticated user.
    *
    * @param array $query
    * @return array{status:int,body:string|array}
    */
    public function exportForCurrentUser(array $query): array
    {
        $userId = (new AuthService())->currentUserId();
        if (!$userId) {
            return ['status' => 401, 'body' => ['message' => 'Unauthorized']];
        }

        $cardId = $this->normalizeInt($query['card_id'] ?? null);
        $contactModel = new Contact();
        $rows = $contactModel->allForUser($userId, $cardId, 2000);

        $headers = ['Name', 'Email', 'Phone', 'Card', 'Card Slug', 'Source URL', 'Created At'];
        $lines = [implode(',', array_map([$this, 'escapeCsv'], $headers))];

        foreach ($rows as $row) {
            $line = [
                $this->escapeCsv($row['name'] ?? ''),
                $this->escapeCsv($row['email'] ?? ''),
                $this->escapeCsv($row['phone'] ?? ''),
                $this->escapeCsv($row['card_name'] ?? ''),
                $this->escapeCsv($row['card_slug'] ?? ''),
                $this->escapeCsv($row['source_url'] ?? ''),
                $this->escapeCsv($row['created_at'] ?? ''),
            ];
            $lines[] = implode(',', $line);
        }

        return [
            'status' => 200,
            'body' => implode("\n", $lines),
        ];
    }

    protected function escapeCsv(string $value): string
    {
        $needsQuotes = strpbrk($value, "\",\n\r") !== false;
        $escaped = str_replace('"', '""', $value);
        return $needsQuotes ? "\"{$escaped}\"" : $escaped;
    }
}
