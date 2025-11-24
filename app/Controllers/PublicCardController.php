<?php

namespace App\Controllers;

use App\Models\Card;
use App\Core\Request;
use App\Core\Response;
use App\Core\Config;

class PublicCardController
{
    public function show(Request $request, int $id): void
    {
        $card = Card::findWithItems($id);

        if (!$card) {
            http_response_code(404);
            echo 'Card not found';
            return;
        }

        $qrImageUrl = $card['qr_image'] ?? null;
        if (!$qrImageUrl) {
            $publicRoot = dirname(__DIR__, 2) . '/public';
            $matches = glob($publicRoot . "/qrcodes/card-{$id}-*.png");
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

        $name = htmlspecialchars($card['name'], ENT_QUOTES, 'UTF-8');

        echo "<!doctype html>";
        echo "<html lang=\"en\"><head><meta charset=\"UTF-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
        echo "<title>{$name}</title>";
        echo "<style>
                body { font-family: Arial, sans-serif; background: #f7f7f7; margin: 0; padding: 24px; }
                .card { max-width: 520px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
                .title { margin: 0 0 12px; font-size: 24px; }
                .item { padding: 12px 0; border-bottom: 1px solid #e5e5e5; }
                .item:last-child { border-bottom: none; }
                .label { display: block; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; }
                .value { display: block; color: #111827; font-size: 16px; font-weight: 600; margin-top: 4px; word-break: break-word; }
            </style></head><body>";
        echo "<div class=\"card\">";
        echo "<h1 class=\"title\">{$name}</h1>";

        if (!empty($qrImageUrl)) {
            $qr = htmlspecialchars($qrImageUrl, ENT_QUOTES, 'UTF-8');
            echo "<div style=\"text-align:center;margin-bottom:16px;\"><img src=\"{$qr}\" alt=\"QR Code\" style=\"max-width:220px;width:100%;height:auto;\" /></div>";
        }

        foreach ($card['items'] as $item) {
            $label = $item['label'] ? htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') : null;
            $value = htmlspecialchars($item['value'], ENT_QUOTES, 'UTF-8');

            echo "<div class=\"item\">";
            if ($label) {
                echo "<span class=\"label\">{$label}</span>";
                echo "<span class=\"value\">{$value}</span>";
            } else {
                echo "<span class=\"value\">{$value}</span>";
            }
            echo "</div>";
        }

        echo "</div></body></html>";
    }
}
