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
$getItemValue = function (string $type) use ($items): ?string {
    foreach ($items as $item) {
        if (($item['type'] ?? '') === $type) {
            $value = trim((string) ($item['label'] ?? $item['value'] ?? ''));
            return $value !== '' ? $value : null;
        }
    }
    return null;
};
$companyName = $getItemValue('company');
$nameOption = $getItemValue('name');
$displayName = $companyName ?: $nameOption ?: null;
$saveName = $displayName ?: 'New Contact';
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
        <a href="https://cardikit.com" class="brand">
            <img src="/assets/smaller-logo-no-background.png" alt="Cardikit logo" />
            <span>Powered by Cardikit</span>
        </a>
        <button class="floating-save" type="button" data-save-trigger>
            Save contact
        </button>
        <div class="contact-modal" data-contact-modal hidden>
            <div class="contact-modal__backdrop" data-close-modal></div>
            <div class="contact-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="contact-modal-title">
                <button class="contact-modal__close" type="button" aria-label="Close" data-close-modal>&times;</button>
                <p class="contact-modal__eyebrow">Stay connected</p>
                <h2 id="contact-modal-title">Save <?= $escape($saveName); ?>'s details</h2>
                <div class="contact-modal__section">
                    <div>
                        <h3>Save to contacts</h3>
                        <p>Download a vCard for <?= $escape($saveName); ?> so you can add them in one tap.</p>
                    </div>
                    <button class="contact-modal__btn" type="button" data-save-contact>Save to contacts</button>
                </div>
                <div class="contact-modal__section">
                    <div>
                        <h3>
                            Share your contact information<?= $displayName ? ' with ' . $escape($displayName) : '' ?>?
                        </h3>
                        <p>Send your details so they can reach you.</p>
                    </div>
                    <form class="contact-share" data-share-form>
                        <label class="contact-field">
                            <span>Name</span>
                            <input type="text" name="name" autocomplete="name" placeholder="Your name" />
                        </label>
                        <label class="contact-field">
                            <span>Email</span>
                            <input type="email" name="email" autocomplete="email" placeholder="you@example.com" />
                        </label>
                        <label class="contact-field">
                            <span>Phone</span>
                            <input type="tel" name="phone" autocomplete="tel" placeholder="+1 (555) 555-5555" />
                        </label>
                        <div class="contact-share__actions">
                            <button class="contact-modal__btn contact-modal__btn--ghost" type="submit">Share my details</button>
                            <p class="contact-share__feedback" data-share-feedback role="status" aria-live="polite"></p>
                        </div>
                    </form>
                </div>
                <div class="contact-modal__section contact-modal__section--cta">
                    <div>
                        <h3>Get Cardikit to create your own digital business cards</h3>
                        <p>Stand out with a beautiful, trackable digital card in minutes.</p>
                    </div>
                    <a class="contact-modal__btn contact-modal__btn--link" href="https://cardikit.com" target="_blank" rel="noopener noreferrer">
                        Get Cardikit for free
                    </a>
                </div>
            </div>
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

            const cardData = {
                name: <?= json_encode($displayName ?? ''); ?>,
                slug: cardSlug,
                items: <?= json_encode($items); ?>,
            };

            const modal = document.querySelector('[data-contact-modal]');
            const saveTriggers = document.querySelectorAll('[data-save-trigger]');
            const closeButtons = document.querySelectorAll('[data-close-modal]');
            const saveButton = document.querySelector('[data-save-contact]');
            const shareForm = document.querySelector('[data-share-form]');
            const shareFeedback = document.querySelector('[data-share-feedback]');
            const cardikitCta = document.querySelector('.contact-modal__btn--link');

            const track = (eventName, meta = {}) => {
                sendEvent({
                    ...basePayload,
                    event_type: 'click',
                    event_name: eventName,
                    meta: { ...basePayload.meta, ...meta },
                });
            };

            const toggleBodyScroll = (shouldLock) => {
                document.body.classList.toggle('contact-modal-open', shouldLock);
            };

            const openModal = () => {
                if (!modal) return;
                modal.hidden = false;
                modal.classList.add('is-visible');
                toggleBodyScroll(true);
                track('open_contact_modal');
            };

            const closeModal = () => {
                if (!modal) return;
                modal.classList.remove('is-visible');
                toggleBodyScroll(false);
                window.setTimeout(() => { modal.hidden = true; }, 180);
            };

            saveTriggers.forEach((btn) => btn.addEventListener('click', openModal));
            closeButtons.forEach((btn) => btn.addEventListener('click', closeModal));

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') closeModal();
            });

            const findItemValue = (type) => {
                const match = (cardData.items || []).find((item) => (item.type || '').toLowerCase() === type);
                if (!match) return '';
                return (match.value || match.label || '').toString().trim();
            };

            const escapeVCard = (value) => value.replace(/,/g, '\\,').replace(/;/g, '\\;').replace(/\n/g, '\\n');

            const buildNameParts = (full) => {
                if (!full) return ['Contact', '', '', '', ''];
                const parts = full.trim().split(/\s+/);
                if (parts.length === 1) return ['', escapeVCard(parts[0]), '', '', ''];
                const last = escapeVCard(parts.pop() || '');
                const first = escapeVCard(parts.join(' '));
                return [last, first, '', '', ''];
            };

            const buildVCard = () => {
                const fullName = cardData.name || findItemValue('name') || 'New Contact';
                const [last, first, middle, prefix, suffix] = buildNameParts(fullName);
                const jobTitle = findItemValue('job_title');
                const company = findItemValue('company');
                const phone = findItemValue('phone');
                const email = findItemValue('email');
                const website = findItemValue('website') || findItemValue('link') || findItemValue('portfolio');
                const address = findItemValue('address');
                const headline = findItemValue('headline') || findItemValue('bio');

                const lines = [
                    'BEGIN:VCARD',
                    'VERSION:3.0',
                    `FN:${escapeVCard(fullName)}`,
                    `N:${last};${first};${middle};${prefix};${suffix}`,
                ];
                if (jobTitle) lines.push(`TITLE:${escapeVCard(jobTitle)}`);
                if (company) lines.push(`ORG:${escapeVCard(company)}`);
                if (phone) lines.push(`TEL;TYPE=CELL:${escapeVCard(phone.replace(/[^0-9+]/g, '') || phone)}`);
                if (email) lines.push(`EMAIL;TYPE=INTERNET:${escapeVCard(email)}`);
                if (website) lines.push(`URL:${escapeVCard(website)}`);
                if (address) lines.push(`ADR;TYPE=HOME:;;${escapeVCard(address)};;;;`);
                if (headline) lines.push(`NOTE:${escapeVCard(headline)}`);
                lines.push('END:VCARD');
                return lines.join('\n');
            };

            const downloadVCard = () => {
                const vcard = buildVCard();
                const blob = new Blob([vcard], { type: 'text/vcard;charset=utf-8' });
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `${cardData.slug || 'cardikit-contact'}.vcf`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.setTimeout(() => URL.revokeObjectURL(url), 500);
            };

            if (saveButton) {
                saveButton.addEventListener('click', () => {
                    downloadVCard();
                    track('save_to_contacts');
                });
            }

            const buildShareText = (data) => {
                const parts = [];
                if (data.name) parts.push(`Name: ${data.name}`);
                if (data.email) parts.push(`Email: ${data.email}`);
                if (data.phone) parts.push(`Phone: ${data.phone}`);
                parts.push(`Shared via Cardikit: ${window.location.href}`);
                return parts.join('\n');
            };

            const sendContact = async (data) => {
                try {
                    const res = await fetch('/api/v1/contacts', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            ...data,
                            card_id: cardId,
                            card_slug: cardSlug,
                            card_name: cardData.name,
                            path: window.location.pathname,
                            source_url: window.location.href,
                        }),
                    });
                    const body = await res.json().catch(() => ({}));
                    return {
                        success: res.ok,
                        message: body.message || (res.ok ? 'Your details were sent.' : 'Unable to send details.'),
                        stored: body.stored === true,
                    };
                } catch (_) {
                    return { success: false, message: 'Unable to send details.', stored: false };
                }
            };

            if (shareForm) {
                shareForm.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    if (shareFeedback) shareFeedback.textContent = '';
                    const formData = new FormData(shareForm);
                    const shareData = {
                        name: (formData.get('name') || '').toString().trim(),
                        email: (formData.get('email') || '').toString().trim(),
                        phone: (formData.get('phone') || '').toString().trim(),
                    };
                    const text = buildShareText(shareData);
                    const result = await sendContact(shareData);
                    try {
                        if (navigator.share) {
                            await navigator.share({
                                title: shareData.name || 'My contact details',
                                text,
                            });
                            if (shareFeedback) shareFeedback.textContent = result.message || 'Shared from your device.';
                        } else {
                            if (shareFeedback) shareFeedback.textContent = result.message;
                        }
                        track('share_contact_details', { label: shareData.name || undefined, stored: result.stored ? 1 : 0 });
                    } catch (err) {
                        if (shareFeedback) shareFeedback.textContent = result.message || 'Share cancelled.';
                    }
                });
            }

            if (cardikitCta) {
                cardikitCta.addEventListener('click', () => {
                    track('cardikit_signup_cta', { target: cardikitCta.href });
                });
            }
        })();
    </script>
</body>
</html>
