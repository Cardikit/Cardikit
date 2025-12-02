<?php

namespace App\Controllers;

use App\Models\Card;
use App\Core\Request;
use App\Core\Response;
use App\Core\Config;
use App\Services\ThemeRenderer;
use App\Services\ThemeCatalog;

class PublicCardController
{
    public function show(Request $request, string $slug): void
    {
        $card = Card::findWithItemsBySlug($slug);

        if (!$card) {
            http_response_code(404);
            echo 'Card not found';
            return;
        }

        $id = $card['id'] ?? null;

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

        $catalog = new ThemeCatalog();
        $availableThemes = $catalog->getSlugs();
        $theme = strtolower(trim((string) ($card['theme'] ?? Config::get('CARD_THEME', 'default'))));
        if ($theme === '' || !in_array($theme, $availableThemes, true)) {
            $theme = $availableThemes[0] ?? Config::get('CARD_THEME', 'default');
        }
        $renderer = new ThemeRenderer();
        echo $renderer->render($theme, $card, $qrImageUrl);
    }
}
