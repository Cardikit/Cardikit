<?php

namespace App\Services;

use App\Core\Config;
use App\Core\Database;
use App\Core\Validator;
use App\Models\Card;
use App\Services\ThemeCatalog;
use App\Services\CardItemService;
use App\Services\ImageStorageService;
use App\Services\QrCodeService;

/**
* Contains methods for creating and syncing cards.
*
* @package App\Services
*
* @since 0.0.2
*/
class CardService
{
    /**
    * Retrieves a list of cards for a user.
    *
    * @param int $userId
    *
    * @return array
    *
    * @since 0.0.2
    */
    public function listForUser(int $userId): array
    {
        return Card::userCardsWithItems($userId) ?? [];
    }

    /**
    * Retrieves a specific card for a user.
    *
    * @param int $userId
    * @param int $cardId
    *
    * @return array
    *
    * @since 0.0.2
    */
    public function getOwnedCard(int $userId, int $cardId): array
    {
        $card = (new Card())->findWithItems($cardId);
        if (!$card) {
            return ['status' => 404, 'card' => null];
        }

        if ((int) $card['user_id'] !== $userId) {
            return ['status' => 401, 'card' => null];
        }

        return ['status' => 200, 'card' => $card];
    }

    /**
    * Creates a new card.
    *
    * @param array $payload
    * @param int $userId
    *
    * @return array
    *
    * @since 0.0.2
    */
    public function create(array $payload, int $userId): array
    {
        // Generate a unique slug
        $payload['slug'] = Card::generateUniqueSlug();
        $payload['user_id'] = $userId;

        // Validate theme
        $themes = new ThemeCatalog();
        $availableThemeSlugs = $themes->getSlugs();
        $defaultTheme = $this->pickDefaultTheme($availableThemeSlugs);
        $requestedTheme = isset($payload['theme']) ? strtolower(trim((string) $payload['theme'])) : null;
        if ($requestedTheme !== null && $requestedTheme !== '' && !in_array($requestedTheme, $availableThemeSlugs, true)) {
            return [
                'status' => 422,
                'body' => [
                    'message' => 'Theme not available',
                    'errors' => ['theme' => ['Unsupported theme.']]
                ]
            ];
        }

        // normalize colors and themes
        $payload['color'] = $this->normalizeColor($payload['color'] ?? null, '#1D4ED8');
        $payload['theme'] = $this->normalizeTheme($payload['theme'] ?? null, $defaultTheme, $availableThemeSlugs);

        // normalize banner and avatar
        $hasBanner = array_key_exists('banner_image', $payload);
        $hasAvatar = array_key_exists('avatar_image', $payload);
        $bannerPayload = $hasBanner ? ($payload['banner_image'] ?? '') : null;
        $avatarPayload = $hasAvatar ? ($payload['avatar_image'] ?? '') : null;
        unset($payload['banner_image'], $payload['avatar_image']);

        $cardItemsPayload = $payload['card_items'] ?? [];
        unset($payload['card_items']);

        // Validate name, color, theme
        $validator = new Validator([Card::class => new Card()]);
        $errors = $validator->validateOrErrors($payload, [
            'name' => 'required|min:2|max:50|type:string',
            'color' => 'required|type:string|hexcolor|max:20',
            'theme' => 'type:string|max:50',
        ]);

        if ($errors !== null) {
            return [
                'status' => 422,
                'body' => ['errors' => $errors],
            ];
        }

        // Create the card
        $card = (new Card())->create($payload);
        if (!$card) {
            return [
                'status' => 500,
                'body' => ['message' => 'Card creation failed'],
            ];
        }

        // Create card items
        [$createdItems, $itemErrors] = (new CardItemService($card['id']))->createCardItems($cardItemsPayload);

        // Rollback if there are item errors
        if (!empty($itemErrors)) {
            (new Card())->deleteById($card['id']);
            return [
                'status' => 422,
                'body' => [
                    'message' => 'Card Creation Failed',
                    'card' => $card,
                    'items' => $createdItems,
                    'errors' => $itemErrors,
                ],
            ];
        }

        try {
            // Store banner and avatar
            $imageService = new ImageStorageService();
            $banner = $imageService->storeOrKeep($bannerPayload, $card['id'], 'banner', null, null);
            $avatar = $imageService->storeOrKeep($avatarPayload, $card['id'], 'avatar', null, null);

            // Generate QR code
            $qr = (new QrCodeService())->generateForCard($card['id'], $card['slug']);
            (new Card())->updateById($card['id'], [
                'qr_url' => $qr['card_url'],
                'qr_image' => $qr['image_url'],
            ]);

            // Upsert banner and avatar
            $this->upsertCardImage($card['id'], 'banner', $banner['url'], $banner['path']);
            $this->upsertCardImage($card['id'], 'avatar', $avatar['url'], $avatar['path']);
        } catch (\Throwable $e) {
            return [
                'status' => 500,
                'body' => [
                    'message' => 'QR code generation failed',
                    'error' => $e->getMessage(),
                    'card_id' => $card['id'],
                ],
            ];
        }

        return [
            'status' => 201,
            'body' => [
                'message' => 'Card created successfully',
                'card' => Card::findWithItems($card['id']),
                'items' => $createdItems
            ],
        ];
    }

    /**
    * Updates a card.
    *
    * @param array $payload
    * @param int $cardId
    * @param int $userId
    *
    * @return array
    *
    * @since 0.0.2
    */
    public function update(array $payload, int $cardId, int $userId): array
    {
        $cardModel = new Card();
        $card = $cardModel->findBy('id', $cardId);
        if (!$card) {
            return ['status' => 404, 'body' => ['message' => 'Card not found']];
        }

        if ((int) $card['user_id'] !== $userId) {
            return ['status' => 401, 'body' => ['message' => 'Unauthorized']];
        }

        // ignore slug
        unset($payload['slug']);
        $payload['color'] = $this->normalizeColor($payload['color'] ?? null, $card['color'] ?? '#1D4ED8');

        // validate theme
        $themes = new ThemeCatalog();
        $availableThemeSlugs = $themes->getSlugs();
        $defaultTheme = $this->pickDefaultTheme($availableThemeSlugs, $card['theme'] ?? null);
        $requestedTheme = isset($payload['theme']) ? strtolower(trim((string) $payload['theme'])) : null;
        if ($requestedTheme !== null && $requestedTheme !== '' && !in_array($requestedTheme, $availableThemeSlugs, true)) {
            return [
                'status' => 422,
                'body' => [
                    'message' => 'Theme not available',
                    'errors' => ['theme' => ['Unsupported theme.']]
                ]
            ];
        }
        // check for theme, avatar, banner
        $payload['theme'] = $this->normalizeTheme($payload['theme'] ?? null, $defaultTheme, $availableThemeSlugs);
        $hasBanner = array_key_exists('banner_image', $payload);
        $hasAvatar = array_key_exists('avatar_image', $payload);
        $bannerPayload = $hasBanner ? ($payload['banner_image'] ?? '') : null;
        $avatarPayload = $hasAvatar ? ($payload['avatar_image'] ?? '') : null;
        unset($payload['banner_image'], $payload['avatar_image']);

        $cardItemsPayload = $payload['card_items'] ?? [];
        unset($payload['card_items']);

        $payload['name'] = $payload['name'] ?? $card['name'];

        $existingImages = $this->getCardImages($cardId);

        // validate name
        $validator = new Validator([Card::class => new Card()]);
        $nameRule = 'required|min:2|max:50|type:string';

        // validate payload
        $errors = $validator->validateOrErrors($payload, [
            'name' => $nameRule,
            'color' => 'type:string|hexcolor|max:20',
            'theme' => 'type:string|max:50',
        ]);

        if ($errors !== null) {
            return ['status' => 422, 'body' => ['errors' => $errors]];
        }

        $db = Database::connect();

        try {
            $db->beginTransaction();

            // update card
            $cardModel->updateById($cardId, $payload);

            // update card items
            [, $itemErrors] = (new CardItemService($cardId))->syncCardItems($cardItemsPayload);

            // rollback if item errors
            if (!empty($itemErrors)) {
                $db->rollBack();
                return [
                    'status' => 422,
                    'body' => ['message' => 'Card update failed', 'errors' => $itemErrors],
                ];
            }

            $db->commit();

            // update images
            $imageService = new ImageStorageService();
            $banner = $imageService->storeOrKeep($bannerPayload, $cardId, 'banner', $existingImages['banner']['url'] ?? null, $existingImages['banner']['path'] ?? null);
            $avatar = $imageService->storeOrKeep($avatarPayload, $cardId, 'avatar', $existingImages['avatar']['url'] ?? null, $existingImages['avatar']['path'] ?? null);

            // upsert images
            $this->upsertCardImage($cardId, 'banner', $banner['url'], $banner['path']);
            $this->upsertCardImage($cardId, 'avatar', $avatar['url'], $avatar['path']);
        } catch (\Throwable) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            return ['status' => 500, 'body' => ['message' => 'Card update failed']];
        }

        return [
            'status' => 200,
            'body' => [
                'message' => 'Card updated successfully',
                'card' => Card::findWithItems($cardId)
            ],
        ];
    }

    /**
    * Deletes a card.
    *
    * @param int $cardId
    * @param int $userId
    *
    * @return array
    *
    * @since 0.0.2
    */
    public function delete(int $cardId, int $userId): array
    {
        $cardModel = new Card();
        $card = $cardModel->findBy('id', $cardId);
        if (!$card) {
            return ['status' => 404, 'body' => ['message' => 'Card not found']];
        }

        if ((int) $card['user_id'] !== $userId) {
            return ['status' => 401, 'body' => ['message' => 'Unauthorized']];
        }

        try {
            // delete qr code
            (new QrCodeService())->deleteImage($card['qr_image'] ?? null);
            $existingImages = $this->getCardImages($cardId);

            // delete images
            $this->deleteCardImageFile($existingImages['banner']['path'] ?? null);
            $this->deleteCardImageFile($existingImages['avatar']['path'] ?? null);

        } catch (\Throwable) {
            // swallow cleanup errors
        }

        // delete card
        $cardModel->deleteById($cardId);

        return ['status' => 200, 'body' => ['message' => 'Card deleted successfully']];
    }

    /**
    * Regenerates the QR code for a card.
    *
    * @param int $cardId
    * @param int $userId
    * @param string|null $logoData
    *
    * @return array
    *
    * @since 0.0.2
    */
    public function regenerateQr(int $cardId, int $userId, ?string $logoData): array
    {
        $cardModel = new Card();
        $card = $cardModel->findBy('id', $cardId);
        if (!$card) {
            return ['status' => 404, 'body' => ['message' => 'Card not found']];
        }

        if ((int) $card['user_id'] !== $userId) {
            return ['status' => 401, 'body' => ['message' => 'Unauthorized']];
        }

        try {
            $qr = (new QrCodeService())->generateForCard($cardId, $card['slug'] ?? '', $logoData, $card['qr_image'] ?? null);
        } catch (\InvalidArgumentException $e) {
            return ['status' => 422, 'body' => ['message' => $e->getMessage()]];
        } catch (\Throwable $e) {
            return [
                'status' => 500,
                'body' => ['message' => 'QR code generation failed', 'error' => $e->getMessage()],
            ];
        }

        $cardModel->updateById($cardId, [
            'qr_url' => $qr['card_url'],
            'qr_image' => $qr['image_url'],
        ]);

        return [
            'status' => 200,
            'body' => [
                'message' => 'QR code generated',
                'card_url' => $qr['card_url'],
                'qr_image_url' => $qr['image_url'],
                'qr_image_path' => $qr['image_path'],
            ],
        ];
    }

    /**
    * Retrieves a list of available themes.
    *
    * @return array
    *
    * @since 0.0.2
    */
    public function getThemes(): array
    {
        return (new ThemeCatalog())->getThemes();
    }

    /**
    * Normalize color input to a #HEX format or fallback when invalid/empty.
    *
    * @param string|null $raw
    * @param string $fallback
    *
    * @return string
    *
    * @since 0.0.2
    */
    protected function normalizeColor(?string $raw, string $fallback): string
    {
        $trimmed = trim((string) $raw);
        if ($trimmed === '') {
            return $fallback;
        }

        if (preg_match('/^#?([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $trimmed, $m)) {
            return '#' . strtoupper($m[1]);
        }

        return $fallback;
    }

    /**
    * Normalize theme input to a slug or fallback when invalid/empty.
    *
    * @param string|null $raw
    * @param string $fallback
    * @param array $allowed
    *
    * @return string
    *
    * @since 0.0.2
    */
    protected function normalizeTheme(?string $raw, string $fallback, array $allowed): string
    {
        $theme = strtolower(trim((string) $raw));

        if ($theme !== '' && in_array($theme, $allowed, true)) {
            return $theme;
        }

        if ($fallback !== '' && in_array($fallback, $allowed, true)) {
            return $fallback;
        }

        return $allowed[0] ?? 'default';
    }

    /**
    * Pick a default theme.
    *
    * @param array $allowed
    * @param string|null $preferred
    *
    * @return string
    *
    * @since 0.0.2
    */
    protected function pickDefaultTheme(array $allowed, ?string $preferred = null): string
    {
        $preferred = trim((string) $preferred);
        if ($preferred !== '' && in_array(strtolower($preferred), $allowed, true)) {
            return strtolower($preferred);
        }

        $env = Config::get('CARD_THEME', 'default');
        if ($env && in_array(strtolower($env), $allowed, true)) {
            return strtolower($env);
        }

        return $allowed[0] ?? 'default';
    }

    /**
    * Upsert a card image.
    *
    * @param int $cardId
    * @param string $type
    * @param string|null $url
    * @param string|null $path
    *
    * @return void
    *
    * @since 0.0.2
    */
    protected function upsertCardImage(int $cardId, string $type, ?string $url, ?string $path): void
    {
        $db = Database::connect();

        if ($url === null || $path === null) {
            $stmt = $db->prepare('DELETE FROM card_images WHERE card_id = :card_id AND type = :type');
            $stmt->execute(['card_id' => $cardId, 'type' => $type]);
            return;
        }

        $stmt = $db->prepare("
            INSERT INTO card_images (card_id, type, image_url, image_path)
            VALUES (:card_id, :type, :url, :path)
            ON DUPLICATE KEY UPDATE image_url = VALUES(image_url), image_path = VALUES(image_path)
        ");
        $stmt->execute([
            'card_id' => $cardId,
            'type' => $type,
            'url' => $url,
            'path' => $path,
        ]);
    }

    /**
    * Delete a card image file.
    *
    * @param string|null $path
    *
    * @return void
    *
    * @since 0.0.2
    */
    protected function deleteCardImageFile(?string $path): void
    {
        if ($path && is_file($path)) {
            @unlink($path);
        }
    }

    /**
    * Get a card's images.
    *
    * @param int $cardId
    *
    * @return array
    *
    * @since 0.0.2
    */
    protected function getCardImages(int $cardId): array
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT type, image_url, image_path FROM card_images WHERE card_id = :card_id");
        $stmt->execute(['card_id' => $cardId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $result = [
            'banner' => ['url' => null, 'path' => null],
            'avatar' => ['url' => null, 'path' => null],
        ];

        foreach ($rows as $row) {
            $type = $row['type'];
            if ($type === 'banner' || $type === 'avatar') {
                $result[$type] = [
                    'url' => $row['image_url'] ?? null,
                    'path' => $row['image_path'] ?? null,
                ];
            }
        }

        return $result;
    }
}
