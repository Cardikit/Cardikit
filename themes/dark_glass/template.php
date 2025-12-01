<?php
/**
 * Default public card theme.
 *
 * Available variables:
 * - $card: array{name?:string,color?:string,banner_image?:?string,avatar_image?:?string,items?:array,type?:string}
 * - $qrImageUrl: ?string
 */

$escape = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$color = $card['color'] ?? '#1D4ED8';
$accent = preg_match('/^#[0-9A-Fa-f]{6}$/', $color) ? strtolower($color) : '#1d4ed8';
$items = is_array($card['items'] ?? null) ? $card['items'] : [];
$banner = $card['banner_image'] ?? null;
$avatar = $card['avatar_image'] ?? null;
$title = $escape($card['name'] ?? 'Card');

$typeNames = [
    'name' => 'Name',
    'job_title' => 'Job Title',
    'department' => 'Department',
    'company' => 'Company',
    'headline' => 'Headline',
    'phone' => 'Phone',
    'email' => 'Email',
    'link' => 'Link',
    'address' => 'Address',
    'website' => 'Website',
    'linkedin' => 'LinkedIn',
    'instagram' => 'Instagram',
    'calendly' => 'Calendly',
    'x' => 'X',
    'facebook' => 'Facebook',
    'threads' => 'Threads',
    'snapchat' => 'Snapchat',
    'tiktok' => 'TikTok',
    'youtube' => 'YouTube',
    'github' => 'GitHub',
    'yelp' => 'Yelp',
    'venmo' => 'Venmo',
    'paypal' => 'PayPal',
    'cashapp' => 'CashApp',
    'discord' => 'Discord',
    'skype' => 'Skype',
    'telegram' => 'Telegram',
    'twitch' => 'Twitch',
    'whatsapp' => 'WhatsApp',
    'pronouns' => 'Pronouns',
    'bio' => 'Bio',
    'portfolio' => 'Portfolio',
];

$formatType = function (?string $type) use ($typeNames): string {
    if (!$type) return 'Detail';
    return $typeNames[$type] ?? ucwords(str_replace('_', ' ', $type));
};

$normalizeUrl = function (string $value): string {
    $trimmed = trim($value);
    if ($trimmed === '') return '';
    if (preg_match('/^https?:\\/\\//i', $trimmed)) {
        return $trimmed;
    }
    return 'https://' . $trimmed;
};

$buildHref = function (?string $type, string $value) use ($normalizeUrl): ?string {
    $clean = trim($value);
    if ($clean === '') return null;

    if ($type === 'phone') {
        $digits = preg_replace('/[^0-9+]/', '', $clean);
        return $digits ? 'tel:' . $digits : null;
    }

    if ($type === 'email') {
        return 'mailto:' . $clean;
    }

    $linkTypes = [
        'link', 'website', 'linkedin', 'instagram', 'calendly', 'x', 'facebook', 'threads',
        'snapchat', 'tiktok', 'youtube', 'github', 'yelp', 'venmo', 'paypal', 'cashapp',
        'discord', 'skype', 'telegram', 'twitch', 'whatsapp', 'portfolio'
    ];

    if (in_array($type, $linkTypes, true)) {
        return $normalizeUrl($clean);
    }

    if (filter_var($clean, FILTER_VALIDATE_EMAIL)) {
        return 'mailto:' . $clean;
    }

    if (preg_match('/^(https?:)?\\/\\//i', $clean) || preg_match('/^[\\w.-]+\\.[a-z]{2,}/i', $clean)) {
        return $normalizeUrl($clean);
    }

    if (preg_match('/^[+\\d][\\d\\s().-]+$/', $clean)) {
        $digits = preg_replace('/[^0-9+]/', '', $clean);
        return $digits ? 'tel:' . $digits : null;
    }

    return null;
};
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title; ?></title>
    <style>
        <?php
            $cssPath = __DIR__ . '/style.css';
            if (is_file($cssPath)) {
                echo file_get_contents($cssPath);
            }
        ?>
        :root { --accent: <?= $escape($accent); ?>; }
    </style>
</head>
<body>
    <div class="page">
        <div class="card-shell">
            <div class="banner" role="presentation" style="<?= $banner ? 'background-image: linear-gradient(120deg, rgba(0,0,0,0.25), rgba(0,0,0,0.45)), url(\'' . $escape($banner) . '\');' : ''; ?>"></div>
            <div class="card-body">
                <div class="header">
                    <?php if (!empty($avatar)): ?>
                        <div class="avatar">
                                <img src="<?= $escape($avatar); ?>" alt="Avatar for <?= $title; ?>" />
                        </div>
                    <?php else: ?>
                        <div>
                        </div>
                    <?php endif; ?>
                    <div>
                        <p class="eyebrow">Digital business card</p>
                        <h1><?= $title; ?></h1>
                    </div>
                    <?php if (!empty($qrImageUrl)): ?>
                        <div class="qr">
                            <img src="<?= $escape($qrImageUrl); ?>" alt="QR code for <?= $title; ?>" />
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($items)): ?>
                    <div class="items">
                        <?php foreach ($items as $item): ?>
                            <?php
                                $type = $item['type'] ?? null;
                                $label = isset($item['label']) ? trim((string) $item['label']) : '';
                                $value = trim((string) ($item['value'] ?? ''));
                                if ($value === '' && $label === '') continue;

                                $leftLabel = $formatType($type);
                                $primary = $label !== '' ? $label : $value;
                                $secondary = $label !== '' ? $value : '';
                                $linkTarget = $value !== '' ? $value : $primary;
                                $href = $buildHref($type, $linkTarget);
                            ?>
                            <div class="item">
                                <span class="item-label"><?= $escape($leftLabel); ?></span>
                                <div class="item-text">
                                    <?php if ($href): ?>
                                        <a class="item-link item-value" href="<?= $escape($href); ?>" target="_blank" rel="noopener noreferrer">
                                            <?= $escape($primary); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="item-value"><?= $escape($primary); ?></span>
                                    <?php endif; ?>
                                    <?php if ($secondary !== ''): ?>
                                        <span class="item-subvalue"><?= $escape($secondary); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="brand">
            <img src="http://localhost:8080/assets/smaller-logo-no-background.png" alt="Cardikit logo" />
            <span>Powered by Cardikit</span>
        </div>
    </div>
</body>
</html>
