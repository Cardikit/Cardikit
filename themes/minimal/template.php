<?php
/**
 * Minimal public card theme.
 *
 * Variables:
 * - $card: array{name?:string,color?:string,banner_image?:?string,avatar_image?:?string,items?:array,type?:string}
 * - $qrImageUrl: ?string
 */

$escape = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$color = $card['color'] ?? '#2563EB';
$accent = preg_match('/^#[0-9A-Fa-f]{6}$/', $color) ? strtolower($color) : '#2563eb';
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
    <title><?= $title; ?> | Cardikit Digital Card</title>
    <meta name="description" content="View <?= $title; ?>'s digital business card, created with Cardikit." />
    <meta property="og:type" content="profile" />
    <meta property="og:title" content="<?= $title; ?> | Cardikit Digital Card" />
    <meta property="og:description" content="Tap to view and save <?= $title; ?>'s contact details." />
    <?php
        $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
        $logoUrl = $appUrl !== '' ? $appUrl . '/assets/smaller-logo-no-background.png' : '/assets/smaller-logo-no-background.png';
        $shareImage = $banner ?: ($qrImageUrl ?: $logoUrl);
    ?>
    <meta property="og:image" content="<?= $escape($shareImage); ?>" />
    <meta property="og:image:alt" content="<?= $escape($title); ?>'s card" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $escape($logoUrl); ?>" />
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
            <div class="banner"></div>
            <div class="card-body">
                <div class="header">
                    <div class="avatar">
                        <?php if (!empty($avatar)): ?>
                            <img src="<?= $escape($avatar); ?>" alt="Avatar for <?= $title; ?>" />
                        <?php else: ?>
                            <span class="eyebrow">Cardikit</span>
                        <?php endif; ?>
                    </div>
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
                            <div class="item" data-analytics-type="<?= $escape($type ?? 'item'); ?>" data-analytics-label="<?= $escape($primary); ?>">
                                <span class="item-label"><?= $escape($leftLabel); ?></span>
                                <div class="item-text">
                                    <?php if ($href): ?>
                                        <a class="item-link item-value" href="<?= $escape($href); ?>" target="_blank" rel="noopener noreferrer" data-analytics-target="<?= $escape($linkTarget); ?>">
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
            <img src="/assets/smaller-logo-no-background.png" alt="Cardikit logo" />
            <span>Powered by Cardikit</span>
        </div>
    </div>
    <script>
        (() => {
            const endpoint = '/api/v1/analytics/events';
            const pathSlug = (() => {
                const parts = window.location.pathname.split('/').filter(Boolean);
                return parts[parts.length - 1] || null;
            })();
            const cardSlug = <?= json_encode($card['slug'] ?? null); ?> || pathSlug;
            const cardId = <?= json_encode($card['id'] ?? null); ?>;
            const theme = <?= json_encode(basename(__DIR__)); ?>;
            const params = new URLSearchParams(window.location.search);
            const rawReferrer = document.referrer || '';
            const referrerHost = (() => {
                try {
                    return rawReferrer ? new URL(rawReferrer).host : null;
                } catch (_) {
                    return null;
                }
            })();
            const entryHint = (params.get('source') || params.get('via') || '').toLowerCase();
            const entryType = entryHint.includes('qr') ? 'qr' : (entryHint.includes('nfc') ? 'nfc' : null);
            const storageKey = cardSlug ? `cardikit:viewed:${cardSlug}` : null;
            let isNewView = false;
            if (storageKey) {
                try {
                    isNewView = !localStorage.getItem(storageKey);
                    localStorage.setItem(storageKey, '1');
                } catch (_) {
                    isNewView = false;
                }
            }

            const basePayload = {
                card_slug: cardSlug,
                card_id: cardId,
                referrer: rawReferrer || null,
                source: referrerHost,
                card_theme: theme,
                meta: {
                    theme,
                    path: window.location.pathname,
                    entry: entryType || undefined,
                    search: window.location.search || undefined,
                },
            };

            const sendEvent = (body) => {
                const payload = JSON.stringify(body);

                const doFetch = () => fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: payload,
                    keepalive: true,
                    credentials: 'same-origin',
                });

                try {
                    if (navigator.sendBeacon) {
                        const blob = new Blob([payload], { type: 'application/json' });
                        const ok = navigator.sendBeacon(endpoint, blob);
                        if (ok) return;
                    }
                    doFetch().catch(() => {});
                } catch (err) {
                    console.debug('analytics send failed', err);
                }
            };

            sendEvent({
                ...basePayload,
                event_type: 'view',
                event_name: entryType === 'qr' ? 'qr_scan' : (entryType === 'nfc' ? 'nfc_scan' : 'card_view'),
                is_new_view: isNewView,
            });

            const mapEventName = (type) => {
                if (!type) return 'cta';
                const t = type.toLowerCase();
                if (t === 'phone') return 'call';
                if (t === 'email') return 'email';
                if (t === 'website' || t === 'link' || t === 'portfolio') return 'website';
                const socials = [
                    'instagram', 'linkedin', 'facebook', 'x', 'threads', 'snapchat', 'tiktok',
                    'youtube', 'github', 'discord', 'telegram', 'whatsapp', 'skype', 'twitch',
                    'yelp', 'venmo', 'paypal', 'cashapp'
                ];
                if (socials.includes(t)) return 'social';
                return t;
            };

            document.querySelectorAll('.item-link').forEach((link) => {
                link.addEventListener('click', () => {
                    const wrapper = link.closest('[data-analytics-type]');
                    const type = wrapper?.dataset.analyticsType || '';
                    const label = wrapper?.dataset.analyticsLabel || link.textContent?.trim() || '';
                    const target = link.dataset.analyticsTarget || link.href;
                    sendEvent({
                        ...basePayload,
                        event_type: 'click',
                        event_name: mapEventName(type),
                        target,
                        meta: {
                            ...basePayload.meta,
                            label,
                            item_type: type || null,
                        },
                    });
                });
            });
        })();
    </script>
</body>
</html>
