<?php

namespace App\Controllers;

use App\Models\Card;
use App\Core\Response;
use App\Core\Request;
use App\Core\Validator;
use App\Core\Database;
use App\Core\Config;
use App\Services\CardItemService;
use App\Services\QrCodeService;
use App\Services\ImageStorageService;
use App\Services\ThemeCatalog;

class CardController
{
    public function index(): void
    {
        $user_id = $_SESSION['user_id'];

        $cards = Card::userCardsWithItems($user_id);

        Response::json($cards ?? []);
    }

    public function show(Request $request, int $id): void
    {
        $card = (new Card())->findWithItems($id);

        if (!$card) {
            Response::json([
                'message' => 'Card not found'
            ], 404);
            return;
        }

        Response::json($card);
    }

    public function themes(): void
    {
        $themes = (new ThemeCatalog())->getThemes();
        Response::json($themes);
    }

    public function create(Request $request): void
    {
        $data = $request->body();

        // normalize defaults
        $data['color'] = $this->normalizeColor($data['color'] ?? null, '#1D4ED8');
        $themes = new ThemeCatalog();
        $availableThemeSlugs = $themes->getSlugs();
        $defaultTheme = $this->pickDefaultTheme($availableThemeSlugs);
        $requestedTheme = isset($data['theme']) ? strtolower(trim((string) $data['theme'])) : null;
        if ($requestedTheme !== null && $requestedTheme !== '' && !in_array($requestedTheme, $availableThemeSlugs, true)) {
            Response::json([
                'message' => 'Theme not available',
                'errors' => ['theme' => ['Unsupported theme.']]
            ], 422);
            return;
        }
        $data['theme'] = $this->normalizeTheme($data['theme'] ?? null, $defaultTheme, $availableThemeSlugs);
        $hasBanner = array_key_exists('banner_image', $data);
        $hasAvatar = array_key_exists('avatar_image', $data);
        $bannerPayload = $hasBanner ? ($data['banner_image'] ?? '') : null;
        $avatarPayload = $hasAvatar ? ($data['avatar_image'] ?? '') : null;
        unset($data['banner_image'], $data['avatar_image']);

        $validator = new Validator([Card::class => new Card()]);
        $valid = $validator->validate($data, [
            'name' => 'required|min:2|max:50|type:string|unique:App\Models\Card:name',
            'color' => 'required|type:string|hexcolor|max:20',
            'theme' => 'type:string|max:50',
        ]);

        // return error if input is invalid
        if (!$valid) {
            Response::json([
                'errors' => $validator->errors()
            ], 422);
            return;
        }

        $cardItemsPayload = $data['card_items'] ?? [];
        unset($data['card_items']);

        $data['user_id'] = $_SESSION['user_id'];

        // create card
        $card = (new Card())->create($data);

        if (!$card) {
            Response::json([
                'message' => 'Card creation failed'
            ], 500);
            return;
        }

        // create card items
        [$createdItems, $itemErrors] = (new CardItemService($card['id']))->createCardItems($cardItemsPayload);

        if (!empty($itemErrors)) {
            Response::json([
                'message' => 'Card Creation Failed',
                'card' => $card,
                'items' => $createdItems,
                'errors' => $itemErrors,
            ], 422);

            // delete card if card items failed
            (new Card())->deleteById($card['id']);

            return;
        }

        // generate and persist QR after successful create
        try {
            $imageService = new ImageStorageService();
            $banner = $imageService->storeOrKeep($bannerPayload, $card['id'], 'banner', null, null);
            $avatar = $imageService->storeOrKeep($avatarPayload, $card['id'], 'avatar', null, null);

            $qr = (new QrCodeService())->generateForCard($card['id']);
            (new Card())->updateById($card['id'], [
                'qr_url' => $qr['card_url'],
                'qr_image' => $qr['image_url'],
            ]);

            $this->upsertCardImage($card['id'], 'banner', $banner['url'], $banner['path']);
            $this->upsertCardImage($card['id'], 'avatar', $avatar['url'], $avatar['path']);
        } catch (\Throwable $e) {
            Response::json([
                'message' => 'QR code generation failed',
                'error' => $e->getMessage(),
                'card_id' => $card['id'],
            ], 500);
            return;
        }

        // return success message
        Response::json([
            'message' => 'Card created successfully',
            'card' => Card::findWithItems($card['id']),
            'items' => $createdItems
        ], 201);
    }

    public function update(Request $request, int $id): void
    {
        $data = $request->body();

        // Ensure card exists
        $cardModel = new Card();
        $card = $cardModel->findBy('id', $id);

        if (!$card) {
            Response::json([
                'message' => 'Card not found'
            ], 404);
            return;
        }

        // Check card owner
        if ($card['user_id'] !== $_SESSION['user_id']) {
            Response::json([
                'message' => 'Unauthorized'
            ], 401);
            return;
        }

        $data['color'] = $this->normalizeColor($data['color'] ?? null, $card['color'] ?? '#1D4ED8');
        $themes = new ThemeCatalog();
        $availableThemeSlugs = $themes->getSlugs();
        $defaultTheme = $this->pickDefaultTheme($availableThemeSlugs, $card['theme'] ?? null);
        $requestedTheme = isset($data['theme']) ? strtolower(trim((string) $data['theme'])) : null;
        if ($requestedTheme !== null && $requestedTheme !== '' && !in_array($requestedTheme, $availableThemeSlugs, true)) {
            Response::json([
                'message' => 'Theme not available',
                'errors' => ['theme' => ['Unsupported theme.']]
            ], 422);
            return;
        }
        $data['theme'] = $this->normalizeTheme($data['theme'] ?? null, $defaultTheme, $availableThemeSlugs);
        $hasBanner = array_key_exists('banner_image', $data);
        $hasAvatar = array_key_exists('avatar_image', $data);
        $bannerPayload = $hasBanner ? ($data['banner_image'] ?? '') : null;
        $avatarPayload = $hasAvatar ? ($data['avatar_image'] ?? '') : null;
        unset($data['banner_image'], $data['avatar_image']);

        $cardItemsPayload = $data['card_items'] ?? [];
        unset($data['card_items']);

        // keep existing name if not provided
        $data['name'] = $data['name'] ?? $card['name'];

        $existingImages = $this->getCardImages($id);

        $validator = new Validator([Card::class => new Card()]);
        $nameRule = 'required|min:2|max:50|type:string';
        if ($data['name'] !== $card['name']) {
            $nameRule .= '|unique:App\Models\Card:name';
        }
        $valid = $validator->validate($data, [
            'name' => $nameRule,
            'color' => 'type:string|hexcolor|max:20',
            'theme' => 'type:string|max:50',
        ]);

        // return error if input is invalid
        if (!$valid) {
            Response::json([
                'errors' => $validator->errors()
            ], 422);
            return;
        }

        $db = Database::connect();

        try {
            $db->beginTransaction();

            // update card
            $cardModel->updateById($id, $data);

            // sync card items
            [, $itemErrors] = (new CardItemService($id))->syncCardItems($cardItemsPayload);

            if (!empty($itemErrors)) {
                $db->rollBack();
                Response::json([
                    'message' => 'Card update failed',
                    'errors' => $itemErrors
                ], 422);
                return;
            }

            $db->commit();

            // Apply image changes after successful DB transaction to avoid orphaned files on rollback
            $imageService = new ImageStorageService();
            $banner = $imageService->storeOrKeep($bannerPayload, $id, 'banner', $existingImages['banner']['url'] ?? null, $existingImages['banner']['path'] ?? null);
            $avatar = $imageService->storeOrKeep($avatarPayload, $id, 'avatar', $existingImages['avatar']['url'] ?? null, $existingImages['avatar']['path'] ?? null);

            $this->upsertCardImage($id, 'banner', $banner['url'], $banner['path']);
            $this->upsertCardImage($id, 'avatar', $avatar['url'], $avatar['path']);
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            Response::json([
                'message' => 'Card update failed'
            ], 500);
            return;
        }

        // return success message
        Response::json([
            'message' => 'Card updated successfully',
            'card' => Card::findWithItems($id)
        ], 200);
    }

    public function delete(Request $request, int $id): void
    {
        // Ensure card exists
        $cardModel = new Card();
        $card = $cardModel->findBy('id', $id);

        if (!$card) {
            Response::json([
                'message' => 'Card not found'
            ], 404);
            return;
        }

        // Check card owner
        if ($card['user_id'] !== $_SESSION['user_id']) {
            Response::json([
                'message' => 'Unauthorized'
            ], 401);
            return;
        }

        // delete associated QR image file
        try {
            (new QrCodeService())->deleteImage($card['qr_image'] ?? null);
            $existingImages = $this->getCardImages($id);
            $this->deleteCardImageFile($existingImages['banner']['path'] ?? null);
            $this->deleteCardImageFile($existingImages['avatar']['path'] ?? null);
        } catch (\Throwable $e) {
            // swallow cleanup errors
        }

        // delete card
        $cardModel->deleteById($id);

        // return success message
        Response::json([
            'message' => 'Card deleted successfully'
        ], 200);
    }

    public function generateQr(Request $request, int $id): void
    {
        $cardModel = new Card();
        $card = $cardModel->findBy('id', $id);

        if (!$card) {
            Response::json([
                'message' => 'Card not found'
            ], 404);
            return;
        }

        if ($card['user_id'] !== $_SESSION['user_id']) {
            Response::json([
                'message' => 'Unauthorized'
            ], 401);
            return;
        }

        $body = $request->body();
        $logoData = $body['logo'] ?? null;

        try {
            $qr = (new QrCodeService())->generateForCard($id, $logoData, $card['qr_image'] ?? null);
        } catch (\InvalidArgumentException $e) {
            Response::json([
                'message' => $e->getMessage()
            ], 422);
            return;
        } catch (\Throwable $e) {
            Response::json([
                'message' => 'QR code generation failed',
                'error' => $e->getMessage()
            ], 500);
            return;
        }

        // persist QR data to the card (store URL/path, not binary)
        $cardModel->updateById($id, [
            'qr_url' => $qr['card_url'],
            'qr_image' => $qr['image_url'],
        ]);

        Response::json([
            'message' => 'QR code generated',
            'card_url' => $qr['card_url'],
            'qr_image_url' => $qr['image_url'],
            'qr_image_path' => $qr['image_path'],
        ], 200);
    }

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

    protected function deleteCardImageFile(?string $path): void
    {
        if ($path && is_file($path)) {
            @unlink($path);
        }
    }

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

    /**
     * Normalize color input to a #HEX format or fallback when invalid/empty.
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
}
