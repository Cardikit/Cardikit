<?php

namespace App\Services;

/**
* Loads a card theme template and renders the public-facing card HTML.
*
* Each theme lives under /themes/{theme}/template.php. The template receives:
*   - $card: array{name?:string,color?:string,banner_image?:?string,avatar_image?:?string,items?:array}
*   - $qrImageUrl: ?string
*
* If a template is missing or fails, we fall back to a minimal built-in view.
*
* @package App\Services
*
* @since 0.0.2
*/
class ThemeRenderer
{
    /**
    * The path to the themes directory.
    *
    * @var string
    *
    * @since 0.0.2
    */
    protected string $themesPath;

    public function __construct(?string $themesPath = null)
    {
        $this->themesPath = $themesPath ?? dirname(__DIR__, 2) . '/themes';
    }

    /**
    * Render a theme by name. Falls back to the bundled template if the theme cannot be loaded.
    *
    * @param string $theme
    * @param array $card
    * @param ?string $qrImageUrl
    *
    * @return string
    *
    * @since 0.0.2
    */
    public function render(string $theme, array $card, ?string $qrImageUrl): string
    {
        $templatePath = $this->themesPath . '/' . $theme . '/template.php';

        $data = [
            'card' => $this->normalizeCard($card),
            'qrImageUrl' => $qrImageUrl,
        ];

        if (is_file($templatePath)) {
            try {
                ob_start();
                extract($data, EXTR_OVERWRITE);
                include $templatePath;
                return (string) ob_get_clean();
            } catch (\Throwable) {
                // Fall through to the builtin renderer below.
            }
        }

        return $this->renderFallback($data['card'], $qrImageUrl);
    }

    /**
    * Ensure expected keys exist so templates can trust the structure.
    *
    * @param array $card
    *
    * @return array
    *
    * @since 0.0.2
    */
    protected function normalizeCard(array $card): array
    {
        $items = $card['items'] ?? [];
        $items = is_array($items) ? $items : [];

        return [
            'name' => $card['name'] ?? 'Card',
            'color' => $card['color'] ?? '#1D4ED8',
            'banner_image' => $card['banner_image'] ?? null,
            'avatar_image' => $card['avatar_image'] ?? null,
            'items' => $items,
        ];
    }

    /**
    * Lightweight built-in theme used as a safety net.
    *
    * @param array $card
    * @param ?string $qrImageUrl
    *
    * @return string
    *
    * @since 0.0.2
    */
    protected function renderFallback(array $card, ?string $qrImageUrl): string
    {
        $escape = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
        $items = $card['items'] ?? [];

        ob_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $escape($card['name']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; margin: 0; padding: 24px; }
        .card { max-width: 520px; margin: 0 auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
        .title { margin: 0 0 12px; font-size: 24px; }
        .item { padding: 12px 0; border-bottom: 1px solid #e5e5e5; }
        .item:last-child { border-bottom: none; }
        .label { display: block; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; }
        .value { display: block; color: #111827; font-size: 16px; font-weight: 600; margin-top: 4px; word-break: break-word; }
    </style>
</head>
<body>
    <div class="card">
        <h1 class="title"><?= $escape($card['name']); ?></h1>
        <?php if (!empty($qrImageUrl)): ?>
            <div style="text-align:center;margin-bottom:16px;">
                <img src="<?= $escape($qrImageUrl); ?>" alt="QR Code" style="max-width:220px;width:100%;height:auto;" />
            </div>
        <?php endif; ?>
        <?php foreach ($items as $item): ?>
            <?php
                $label = isset($item['label']) ? $escape($item['label']) : null;
                $value = $escape($item['value'] ?? '');
            ?>
            <div class="item">
                <?php if ($label): ?>
                    <span class="label"><?= $label; ?></span>
                    <span class="value"><?= $value; ?></span>
                <?php else: ?>
                    <span class="value"><?= $value; ?></span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
<?php
        return (string) ob_get_clean();
    }
}
