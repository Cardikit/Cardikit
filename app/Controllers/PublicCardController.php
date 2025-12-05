<?php

namespace App\Controllers;

use App\Models\Card;
use App\Core\Request;
use App\Core\Response;
use App\Core\Config;
use App\Services\ThemeRenderer;
use App\Services\ThemeCatalog;

/**
* Contains methods to show public cards to end users.
*
* @package App\Controllers
*
* @since 0.0.2
*/
class PublicCardController
{
    /**
    * Displays a specific card.
    *
    * @param Request $request
    * @param string $slug The slug of the card to display.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function show(Request $request, string $slug): void
    {
        // get the card based on the provided slug
        $card = Card::findWithItemsBySlug($slug);

        // Fail if card not found
        if (!$card) {
            http_response_code(404);
            echo 'Card not found';
            return;
        }

        $id = $card['id'] ?? null;

        // get qr image
        $qrImageUrl = $card['qr_image'] ?? null;
        if (!$qrImageUrl) {
            $publicRoot = dirname(__DIR__, 2) . '/public';
            $matches = $id ? glob($publicRoot . "/qrcodes/card-{$id}-*.png") : [];
            if (!empty($matches)) {
                // pick the latest file
                usort($matches, fn($a, $b) => filemtime($b) <=> filemtime($a));
                $fileName = basename($matches[0]);
                $qrImageUrl = rtrim(Config::get('QR_BASE_URL', Config::get('APP_URL', 'http://localhost')), '/') . "/qrcodes/{$fileName}";
            }
        }

        // Allow JSON consumers to fetch the card (including QR data)
        $acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (str_contains($acceptHeader, 'application/json')) {
            Response::json([
                ...$card,
                'qr_image' => $qrImageUrl,
            ]);
            return;
        }

        header('Content-Type: text/html; charset=utf-8');

        // Render the theme with the specified card data
        $catalog = new ThemeCatalog();
        $availableThemes = $catalog->getSlugs();
        $theme = strtolower(trim((string) ($card['theme'] ?? Config::get('CARD_THEME', 'default'))));
        if ($theme === '' || !in_array($theme, $availableThemes, true)) {
            $theme = $availableThemes[0] ?? Config::get('CARD_THEME', 'default');
        }
        $renderer = new ThemeRenderer();
        Response::html($renderer->render($theme, $card, $qrImageUrl));
    }
}
