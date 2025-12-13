<?php
/**
 * Mr Appliance public card theme.
 *
 * Variables:
 * - $card: array{name?:string,slug?:string,id?:int,color?:string,items?:array,banner_image?:?string,avatar_image?:?string}
 * - $qrImageUrl: ?string
 */

$escape = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$items = is_array($card['items'] ?? null) ? $card['items'] : [];
$getItemValue = function (string $type) use ($items): ?string {
    foreach ($items as $item) {
        if (($item['type'] ?? '') === $type) {
            $value = trim((string) ($item['value'] ?? $item['label'] ?? ''));
            return $value !== '' ? $value : null;
        }
    }
    return null;
};

$normalizeUrl = function (?string $value): ?string {
    if (!$value) return null;
    $trimmed = trim($value);
    if ($trimmed === '') return null;
    if (preg_match('/^https?:\\/\\//i', $trimmed)) return $trimmed;
    return 'https://' . $trimmed;
};

$buildHref = function (?string $type, ?string $value) use ($normalizeUrl): ?string {
    $clean = trim((string) ($value ?? ''));
    if ($clean === '') return null;

    if ($type === 'phone') {
        $digits = preg_replace('/[^0-9+]/', '', $clean);
        return $digits ? 'tel:' . $digits : null;
    }

    if ($type === 'email') {
        return 'mailto:' . $clean;
    }

    if ($type === 'website') {
        return $normalizeUrl($clean);
    }

    return null;
};

$name = $getItemValue('name') ?? 'John Doe';
$jobTitle = $getItemValue('job_title') ?? 'Job Title';
$company = $getItemValue('company') ?? 'Company Name';
$phone = $getItemValue('phone') ?? '(555) 555-5555';
$email = $getItemValue('email') ?? 'example@gmail.com';
$website = $getItemValue('website') ?? 'https://example.com';
$address = $getItemValue('address') ?? '123 Main St, City, State 12345';

$title = $escape($card['name'] ?? $name ?? 'Card');
$appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
$logoUrl = $appUrl !== '' ? $appUrl . '/assets/smaller-logo-no-background.png' : '/assets/smaller-logo-no-background.png';
$shareImage = $qrImageUrl ?: $logoUrl;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $company; ?> | <?= $name; ?> | Digital Business Card</title>
    <meta name="description" content="View <?= $company; ?>'s digital business card." />
    <meta property="og:type" content="profile" />
    <meta property="og:title" content="<?= $company; ?> | Cardikit Digital Card" />
    <meta property="og:description" content="Tap to view and save <?= $company; ?>'s contact details." />
    <meta property="og:image" content="<?= $escape($shareImage); ?>" />
    <meta property="og:image:alt" content="<?= $company; ?>'s card" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $escape($logoUrl); ?>" />
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        <?php
            $cssPath = __DIR__ . '/style.css';
            if (is_file($cssPath)) {
                echo file_get_contents($cssPath);
            }
        ?>
    </style>
</head>
<body>
    <div class="floating-elements">
        <div class="float-element">🔧</div>
        <div class="float-element">🏠</div>
        <div class="float-element">⚡</div>
    </div>

    <div class="business-card">
        <div class="card-shine"></div>
        <div class="diagonal-bg"></div>
        
        <div class="card-content">
            <div class="left-section">
                <div class="logo-container">
                    <div class="logo">
                        <svg class="logo-icon" id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 81 67.07">
                          <defs>
                            <style>
                              .st0 {
                                fill: #fff;
                              }
                            </style>
                          </defs>
                          <path class="st0" d="M55.577,16.776s1.754,2.708,1.064,6.572c0,0,6.636-1.902,9.487-4.116,0,0,5.263-2.767,2.312-13.589l-1.205-4.764c-.089-.351-.392-.605-.753-.634-.997-.081-2.987-.087-4.203.948-.235.199-.354.502-.327.809.055.63.263,1.749,1.043,2.328.292.217.504.524.58.88l.077.358s2.135,7.463-1.254,9.496l-6.822,1.713Z"/>
                          <path class="st0" d="M54.336,38.556s-6.564.966-12.034,4.694c-.203.139-.459-.077-.355-.3,0,0,2.592-3.498,8.218-4.904,0,0,3.504-12.683.566-19.881-.181-.443-.623-.723-1.1-.687l-2.812.212s1.233,2.634,1.011,6.223c-.022.356-.283.653-.632.726-1.566.329-6.101,1.146-11.205.823-.346-.021-.633-.274-.693-.615-.213-1.214-.812-4.1-1.974-6.073,0,0-3.791-.13-3.588.674,4.554,17.966-2.724,31.221-2.724,31.221-4.019,6.808-12.187,3.205-13.597,2.516-.12-.058-.264-.018-.338.093-.434.65-1.968,2.776-4.602,4.571-.371.252-.201.639.153.944,19.714,17.016,31.861,2.037,31.861,2.037,15.53-15.115,27.252-8.5,27.455-8.823,1.394-2.227,4.51-3.876,4.51-3.876.215-.185.255-.5.098-.736-5.889-8.837-14.332-9.214-18.216-8.84M38.394,34.103c.084-2.384-.179-4.823-.179-4.823,3.048.238,8.032-.222,8.032-.222.131.958-.052,3.348-.164,4.614-.046.509-.408.937-.906,1.053-1.838.429-4.323.48-5.738.467-.593-.006-1.067-.497-1.046-1.089"/>
                          <path class="st0" d="M69.551,53.371c.055.15,2.02,2.059,2.02,2.059.397.405.682.396,1.492.13,1.792-.588,6.422-4.036,7.762-5.55.267-.313.179-.861-.061-1.172l-.063-.08c-.722-.936-1.705-1.202-2.866-.974-.789.155-1.338.259-2.015.415-.568.13-1.285.475-1.873.854,0,0-4.742,3.382-4.396,4.319"/>
                          <path class="st0" d="M11.59,52.059c-1.274-.804-2.347-2.265-2.461-2.446-.009-.014-.158-.204-.169-.216-.227-.246-.383-.114-1.202.245-1.797.787-6.039,3.678-7.398,5.312-.41.493-.476,1.087-.219,1.42.742.962,1.807,1.195,3.015,1.057.978-.112,2.075-.418,2.755-.589.29-.073.661-.174.885-.37,0,0,3.63-2.567,4.493-3.781.381-.534.395-.574.302-.632"/>
                          <path class="st0" d="M21.288,28.68c.518-.176,3.138-1.293,3.73-1.427.282-3.659-.14-4.68-.744-6.347-.1-.091-2.191.686-2.702.873-3.604,1.327-7.073,5.079-10.074,11.6l-1.536-1.198c.046-1.122-.432-2.249-1.389-2.987-1.089-.839-2.502-.94-3.67-.405l2.364,1.823-2.02,2.623-2.365-1.823c-.219,1.267.237,2.608,1.327,3.448,1.192.92,2.779.968,4,.249l1.161.905c-.283.383-.256.916.08,1.264.725.749,1.628,1.643,3.073,2.109.365.117.744.077,1.065-.083l1.435,1.119c-.022,1.097.455,2.19,1.39,2.911,1.089.841,2.502.941,3.67.406l-2.363-1.824,2.02-2.623,2.364,1.822c.219-1.266-.238-2.607-1.326-3.447-1.21-.933-2.826-.968-4.055-.215l-1.46-1.138.959-2.192s1.722-4.308,5.065-5.444Z"/>
                          <g>
                            <path class="st0" d="M48.94,3.854c-.766-.201-2.238-.327-4.94.192l-.022-.049c-2.776.871-6.903,2.509-8.945,5.313-.079.109-.26.057-.261-.078,0,0,.031-.047.037-.083.08-.472.755-1.346,1.816-2.284-1.015-.997-1.214-2.576-1.251-3.308-.988.68-1.766,1.568-2.071,2.723-.045.169-.044.348.002.516l2.126,7.849c.128.472.484.846.948.997,1.806.585,6.345,1.211,7.315-.865,0,0,2.233-3.371,1.684-6.955,1.608-.672,3.452-1.632,4.091-2.702.292-.489.022-1.121-.529-1.267Z"/>
                            <path class="st0" d="M36.904,6.601s.007.011.011.016c1.666-1.396,4.137-2.86,6.756-3.306l-.57-1.273c-.156-.349-.51-.564-.89-.544-1.328.071-4.284.503-6.457,1.821.008.499.11,2.274,1.151,3.287Z"/>
                          </g>
                        </svg>
                        <div class="logo-text">
                            <div class="company-name">MR. APPLIANCE</div>
                            <div class="tagline">Speedy Expert Service</div>
                            <div class="neighborly">
                                <p>a</p>
                                <svg class="neighborly-logo" version="1.1" baseProfile="tiny" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 251 52" overflow="visible" xml:space="preserve">
                                    <g id="Reviews_x2C_-Expert-Tips_x2C_-and-Violators">
                                        <g id="RPM---Home-Desktop---Corporate_Landing---02" transform="translate(-644.000000, -76.000000)">
                                            <g id="NEI-Top-Bar" transform="translate(0.000000, 60.000000)">
                                                <g id="Neighborly" transform="translate(644.000000, 16.000000)">
                                                    <path id="Path" fill="#1f3a4db3" d="M31.5,17.3l-15.1-7H16h-0.4l-7.4,3.5H7.9v-2.3c0-0.5-0.4-0.9-0.9-0.9H0.9      C0.4,10.6,0,11,0,11.5v24.4c0,0.5,0.4,0.9,0.9,0.9h6c0.5,0,0.9-0.4,0.9-0.9V22.4l0,0l7.9-3.7h0.2l7.9,3.7l0,0V36      c0,0.5,0.4,0.9,0.9,0.9h6c0.5,0,0.9-0.4,0.9-0.9V18.1C31.7,17.8,31.6,17.5,31.5,17.3L31.5,17.3z"/>
                                                    <path id="Path_1_" fill="#1f3a4db3" d="M69,14.2h-6.2c-0.5,0-0.9,0.4-0.9,0.9V36c0,0.2,0.1,0.5,0.3,0.6c0.2,0.2,0.4,0.3,0.6,0.3      H69c0.2,0,0.5-0.1,0.6-0.3c0.2-0.2,0.3-0.4,0.3-0.6V15.1C69.9,14.6,69.5,14.2,69,14.2L69,14.2z"/>
                                                    <path id="Path_2_" fill="#1f3a4db3" d="M65.8,2c-2.5,0-4.5,2.1-4.5,4.6s2.1,4.5,4.6,4.5s4.5-2,4.5-4.6c0-1.2-0.5-2.4-1.3-3.3      C68.3,2.4,67.1,1.9,65.8,2L65.8,2z"/>
                                                    <path id="Path_3_" fill="#1f3a4db3" d="M115.4,13.6c-2.2-0.2-4.3,0.7-5.8,2.4l-0.3,0.4V4.7c0-0.5-0.4-0.9-0.9-0.9h-6.2      c-0.5,0-0.9,0.4-0.9,0.9v31.4c0,0.2,0.1,0.5,0.3,0.6c0.2,0.2,0.4,0.3,0.6,0.3h6.2c0.2,0,0.5-0.1,0.6-0.3      c0.2-0.2,0.3-0.4,0.3-0.6V24.8c0-3.4,2.2-3.7,3.1-3.7c2.6,0,3.1,2.3,3.1,3.7v11.3c0,0.5,0.4,0.9,0.9,0.9h6.2      c0.5,0,0.9-0.4,0.9-0.9V22.2c0.1-2.3-0.7-4.6-2.2-6.3C119.8,14.3,117.6,13.5,115.4,13.6L115.4,13.6z"/>
                                                    <path id="Shape" fill="#1f3a4db3" d="M141.6,13.6c-2.2-0.1-4.3,0.7-5.8,2.3l-0.3,0.3V4.7c0-0.5-0.4-0.9-0.9-0.9h-6.2      c-0.5,0-0.9,0.4-0.9,0.9V36c0,0.5,0.4,0.9,0.9,0.9h5.8c0.2,0,0.5-0.1,0.6-0.3c0.2-0.2,0.3-0.4,0.3-0.6v-1.4l0.3,0.3      c1.6,1.8,3.9,2.7,6.2,2.6c5.9,0,10.5-5.2,10.5-12S147.6,13.6,141.6,13.6z M143.8,25.5c-0.1,2.4-2.1,4.2-4.5,4.1      s-4.2-2.1-4.2-4.4c0-2.4,2-4.3,4.3-4.3c1.2,0,2.4,0.5,3.2,1.3C143.5,23.1,143.9,24.3,143.8,25.5z"/>
                                                    <path id="Shape_1_" fill="#1f3a4db3" d="M167.3,13.6c-6.5,0.3-11.6,5.8-11.4,12.3s5.6,11.7,12.2,11.6c6.5-0.1,11.8-5.4,11.8-12      c0-3.3-1.3-6.4-3.7-8.7S170.6,13.4,167.3,13.6z M171.6,25.5c-0.2,2.3-2.2,4.1-4.5,4s-4.2-2.1-4.1-4.4c0-2.3,2-4.2,4.3-4.2      c1.2,0,2.4,0.5,3.2,1.3S171.7,24.3,171.6,25.5z"/>
                                                    <path id="Shape_2_" fill="#1f3a4db3" d="M97.5,34.4V15.1c0-0.2-0.1-0.5-0.3-0.6c-0.2-0.2-0.4-0.3-0.6-0.3h-5.9      c-0.2,0-0.5,0.1-0.6,0.3c-0.2,0.2-0.3,0.4-0.3,0.6v1.4l-0.3-0.4c-1.5-1.8-3.8-2.7-6.1-2.6c-2.9,0-5.7,1.2-7.6,3.3      c-3.8,4.7-3.8,11.4,0,16.1c1.9,2.1,4.7,3.3,7.6,3.3c2.2,0.1,4.4-0.8,5.9-2.4l0.3-0.4v3.4c0,1.4-0.6,2.8-1.6,3.8      s-2.4,1.5-3.8,1.5c-1.7,0-3.4-0.8-4.4-2.2c-0.2-0.2-0.4-0.4-0.7-0.4h-0.4l-5.4,2.6c-0.2,0.1-0.4,0.3-0.5,0.5s-0.1,0.5,0,0.7      c2.4,4,6.7,6.4,11.4,6.4c7.2,0,13.1-5.9,13.1-13.1V34L97.5,34.4z M85.4,30.1c-1.9,0.1-3.7-0.9-4.5-2.6s-0.5-3.7,0.8-5.1      s3.3-1.8,5.1-1.1c1.8,0.7,2.9,2.4,2.9,4.3c0.1,1.2-0.3,2.4-1.1,3.3C87.8,29.7,86.7,30.2,85.4,30.1L85.4,30.1z"/>
                                                    <path id="Path_4_" fill="#1f3a4db3" d="M197.6,13.7h-1c-2.4-0.2-4.7,0.9-6,2.9l-0.3,0.5v-1.9c0-0.5-0.4-0.9-0.9-0.9h-5.8      c-0.5,0-0.9,0.4-0.9,0.9v20.9c0,0.5,0.4,0.9,0.9,0.9h6.2c0.5,0,0.9-0.4,0.9-0.9V24.9c0-2.4,1.2-3.5,4-3.5c0.8,0,1.6,0.1,2.5,0.2      h0.3c0.2,0,0.4-0.1,0.5-0.2c0.2-0.2,0.3-0.4,0.4-0.7l0.3-6c0.1-0.3,0-0.6-0.2-0.8S197.9,13.7,197.6,13.7L197.6,13.7z"/>
                                                    <path id="Path_5_" fill="#1f3a4db3" d="M209.7,3.8h-6.2c-0.2,0-0.5,0.1-0.6,0.3c-0.2,0.2-0.3,0.4-0.3,0.6V36      c0,0.2,0.1,0.5,0.3,0.6c0.2,0.2,0.4,0.3,0.6,0.3h6.2c0.2,0,0.5-0.1,0.6-0.3c0.2-0.2,0.3-0.4,0.3-0.6V4.7c0-0.2-0.1-0.5-0.3-0.6      C210.2,3.9,209.9,3.8,209.7,3.8L209.7,3.8z"/>
                                                    <path id="Path_00000101099065055189112790000005472872675860023205_" fill="#1f3a4db3" d="M233,23.7c0.1,1.3-0.4,2.7-1.3,3.6      c-0.9,1-2.2,1.6-3.5,1.6c-2.9,0-5.2-2.3-5.2-5.2v-8.6c0-0.5-0.4-0.9-0.9-0.9h-6c-0.2,0-0.5,0.1-0.6,0.3      c-0.2,0.2-0.3,0.4-0.3,0.6v8.6c0,4.2,2.1,8.2,5.5,10.7s7.9,3.1,11.9,1.7h0.2v0.5c0,1.5-0.6,3-1.7,4.1c-1.1,1.1-2.6,1.7-4.1,1.7      c-1.9,0-3.7-0.9-4.8-2.5c-0.2-0.3-0.4-0.4-0.7-0.4H221l-5.4,2.6c-0.2,0.1-0.4,0.3-0.5,0.5s-0.1,0.5,0,0.7      c2.5,4.1,7,6.7,11.8,6.7c3.5,0,7-1.3,9.5-3.8c2.6-2.4,4.1-5.7,4.2-9.2v-0.3l0,0V15.2c0-0.5-0.4-0.9-0.9-0.9H234      c-0.5,0-0.9,0.4-0.9,0.9L233,23.7z"/>
                                                    <path id="Shape_3_" fill="#1f3a4db3" d="M47,13.6c-3.2-0.1-6.3,1.1-8.6,3.4c-2.2,2.4-3.4,5.5-3.3,8.8c-0.1,3.2,1.1,6.3,3.4,8.5      c2.4,2.2,5.6,3.4,8.9,3.2c3.4,0.1,6.8-0.9,9.5-3.1c0.2-0.2,0.3-0.4,0.3-0.7s-0.1-0.5-0.3-0.7l-4.1-3.4c-0.2-0.1-0.4-0.2-0.6-0.2      c-0.2,0-0.4,0.1-0.5,0.2c-1.1,0.9-2.5,1.3-3.9,1.4c-2,0.2-3.8-1-4.4-2.9v-0.2h14.5c0.5,0,0.9-0.4,0.9-0.8c0-0.3,0-0.9,0-1.5      c0.1-3.2-1.1-6.3-3.3-8.6C53.2,14.6,50.2,13.5,47,13.6L47,13.6z M51.3,23.2h-8.5V23c0.2-2.2,2.1-3.8,4.2-3.8      c2.2,0,4,1.6,4.2,3.8L51.3,23.2z"/>
                                                    <path id="Shape_4_" fill="#1f3a4db3" d="M247.1,22.1c-2.2,0-3.9-1.8-3.9-3.9s1.8-3.9,3.9-3.9c2.2,0,3.9,1.8,3.9,3.9      S249.2,22.1,247.1,22.1L247.1,22.1z M250.3,18.1c0-1.8-1.4-3.2-3.2-3.2s-3.2,1.4-3.2,3.2s1.4,3.2,3.2,3.2c0.8,0,1.7-0.3,2.3-0.9      C249.9,19.8,250.3,19,250.3,18.1L250.3,18.1z M248,20.4l-1-1.8h-0.7v1.8h-0.7V16h1.4c1,0,2,0,2,1.3c0,0.7-0.4,1.2-1.1,1.3      l1.1,1.9L248,20.4z M247.1,17.9c0.5,0,1,0,1-0.7s-0.4-0.6-1-0.6h-0.8v1.3C246.3,17.9,247.1,17.9,247.1,17.9z"/>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                <p>Company</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-section">
                    <div class="name"><?= $escape($name); ?></div>
                    <div class="title"><?= $escape($jobTitle); ?></div>
                    <div class="department"><?= $escape($company); ?></div>
                </div>

                <div class="qr-section">
                    <div class="qr-code">
                        <?php if (!empty($qrImageUrl)): ?>
                            <img src="<?= $escape($qrImageUrl); ?>" alt="QR code for <?= $title; ?>" />
                        <?php else: ?>
                            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation">
                                <rect x="0" y="0" width="100" height="100" fill="white"/>
                                <g fill="#1A1A1A">
                                    <rect x="5" y="5" width="20" height="20"/>
                                    <rect x="75" y="5" width="20" height="20"/>
                                    <rect x="5" y="75" width="20" height="20"/>
                                    <rect x="10" y="10" width="10" height="10" fill="white"/>
                                    <rect x="80" y="10" width="10" height="10" fill="white"/>
                                    <rect x="10" y="80" width="10" height="10" fill="white"/>
                                    <rect x="35" y="15" width="5" height="5"/>
                                    <rect x="45" y="15" width="5" height="5"/>
                                    <rect x="55" y="15" width="5" height="5"/>
                                    <rect x="35" y="25" width="5" height="5"/>
                                    <rect x="55" y="25" width="5" height="5"/>
                                    <rect x="35" y="35" width="5" height="5"/>
                                    <rect x="45" y="35" width="5" height="5"/>
                                    <rect x="55" y="35" width="5" height="5"/>
                                    <rect x="15" y="45" width="5" height="5"/>
                                    <rect x="25" y="45" width="5" height="5"/>
                                    <rect x="45" y="45" width="5" height="5"/>
                                    <rect x="65" y="45" width="5" height="5"/>
                                    <rect x="85" y="45" width="5" height="5"/>
                                    <rect x="15" y="55" width="5" height="5"/>
                                    <rect x="35" y="55" width="5" height="5"/>
                                    <rect x="55" y="55" width="5" height="5"/>
                                    <rect x="75" y="55" width="5" height="5"/>
                                    <rect x="45" y="65" width="5" height="5"/>
                                    <rect x="65" y="65" width="5" height="5"/>
                                    <rect x="85" y="65" width="5" height="5"/>
                                    <rect x="35" y="75" width="5" height="5"/>
                                    <rect x="55" y="75" width="5" height="5"/>
                                    <rect x="35" y="85" width="5" height="5"/>
                                    <rect x="45" y="85" width="5" height="5"/>
                                    <rect x="65" y="85" width="5" height="5"/>
                                    <rect x="85" y="85" width="5" height="5"/>
                                </g>
                            </svg>
                        <?php endif; ?>
                    </div>
                    <div class="qr-label">Scan to Save Contact</div>
                </div>
            </div>

            <div class="right-section">
                <div class="contact-info">
                    <?php if ($phone): ?>
                        <div class="contact-item" data-analytics-type="phone" data-analytics-label="Office">
                            <div class="contact-icon">📞</div>
                            <a class="contact-details contact-link" href="<?= $escape($buildHref('phone', $phone)); ?>" data-analytics-target="<?= $escape($phone); ?>">
                                <div class="contact-label">Office</div>
                                <div class="contact-value"><?= $escape($phone); ?></div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ($email): ?>
                        <div class="contact-item" data-analytics-type="email" data-analytics-label="Email">
                            <div class="contact-icon">✉️</div>
                            <a class="contact-details contact-link" href="<?= $escape($buildHref('email', $email)); ?>" data-analytics-target="<?= $escape($email); ?>">
                                <div class="contact-label">Email</div>
                                <div class="contact-value"><?= $escape($email); ?></div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ($website): ?>
                        <div class="contact-item" data-analytics-type="website" data-analytics-label="Website">
                            <div class="contact-icon">🌐</div>
                            <a class="contact-details contact-link" href="<?= $escape($buildHref('website', $website)); ?>" target="_blank" rel="noopener noreferrer" data-analytics-target="<?= $escape($website); ?>">
                                <div class="contact-label">Website</div>
                                <div class="contact-value"><?= $escape($website); ?></div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ($address): ?>
                        <div class="contact-item" data-analytics-type="address" data-analytics-label="Address">
                            <div class="contact-icon">📍</div>
                            <div class="contact-details">
                                <div class="contact-label">Service Area</div>
                                <div class="contact-value"><?= $escape($address); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="actions">
                    <button class="btn btn-primary" type="button" data-save-trigger>
                        <span>Save Contact</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="contact-modal" data-contact-modal hidden>
        <div class="contact-modal__backdrop" data-close-modal></div>
        <div class="contact-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="contact-modal-title">
            <button class="contact-modal__close" type="button" aria-label="Close" data-close-modal>&times;</button>
            <p class="contact-modal__eyebrow">Stay connected</p>
            <h2 id="contact-modal-title">Save <?= $escape($company ?: 'this contact'); ?>'s details</h2>
            <div class="contact-modal__section">
                <div>
                    <h3>Save to contacts</h3>
                    <p>Download a vCard for quick access to <?= $escape($company); ?>.</p>
                </div>
                <button class="contact-modal__btn" type="button" data-save-contact>Save to contacts</button>
            </div>
            <div class="contact-modal__section">
                <div>
                    <h3>Share your contact information</h3>
                    <p>Send your details so we can reach you back.</p>
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
                    <h3>Create your own digital card</h3>
                    <p>Stand out with a trackable Cardikit digital card.</p>
                </div>
                <a class="contact-modal__btn contact-modal__btn--link" href="https://cardikit.com" target="_blank" rel="noopener noreferrer">
                    Try Cardikit for free
                </a>
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
                if (t === 'website') return 'website';
                if (t === 'address') return 'address';
                return t;
            };

            document.querySelectorAll('.contact-link').forEach((link) => {
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

            document.querySelectorAll('.contact-item').forEach((item) => {
                const link = item.querySelector('.contact-link');
                if (!link) return;
                item.addEventListener('click', (event) => {
                    if (event.target.closest('a')) return;
                    link.click();
                });
            });

            const cardData = {
                name: <?= json_encode($name); ?>,
                job_title: <?= json_encode($jobTitle); ?>,
                company: <?= json_encode($company); ?>,
                phone: <?= json_encode($phone); ?>,
                email: <?= json_encode($email); ?>,
                website: <?= json_encode($website); ?>,
                address: <?= json_encode($address); ?>,
                slug: cardSlug,
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
                const fullName = cardData.company || 'New Contact';
                const [last, first, middle, prefix, suffix] = buildNameParts(fullName);
                const phoneDigits = (cardData.phone || '').replace(/[^0-9+]/g, '') || cardData.phone;

                const lines = [
                    'BEGIN:VCARD',
                    'VERSION:3.0',
                    `FN:${escapeVCard(fullName)}`,
                    `N:${last};${first};${middle};${prefix};${suffix}`,
                ];
                if (cardData.job_title) lines.push(`TITLE:${escapeVCard(cardData.job_title)}`);
                if (cardData.company) lines.push(`ORG:${escapeVCard(cardData.company)}`);
                if (cardData.phone) lines.push(`TEL;TYPE=CELL:${escapeVCard(phoneDigits)}`);
                if (cardData.email) lines.push(`EMAIL;TYPE=INTERNET:${escapeVCard(cardData.email)}`);
                if (cardData.website) lines.push(`URL:${escapeVCard(cardData.website)}`);
                if (cardData.address) lines.push(`ADR;TYPE=HOME:;;${escapeVCard(cardData.address)};;;;`);
                lines.push('END:VCARD');
                return lines.join('\n');
            };

            const downloadVCard = () => {
                const vcard = buildVCard();
                const blob = new Blob([vcard], { type: 'text/vcard;charset=utf-8' });
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `${cardData.slug || 'contact'}.vcf`;
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

            const floatElements = document.querySelectorAll('.float-element');
            document.addEventListener('mousemove', (e) => {
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;

                floatElements.forEach((el, index) => {
                    const speed = (index + 1) * 10;
                    const xMove = (x - 0.5) * speed;
                    const yMove = (y - 0.5) * speed;
                    el.style.transform = `translate(${xMove}px, ${yMove}px)`;
                });
            });
        })();
    </script>
</body>
</html>
