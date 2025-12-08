<?php
    $gaId = \App\Core\Config::get('GA_MEASUREMENT_ID', '');
?>
<?php if (!empty($gaId)) : ?>
    <script defer src="https://www.googletagmanager.com/gtag/js?id=<?= esc($gaId); ?>"></script>
    <script defer>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= esc($gaId); ?>');
    </script>
<?php endif; ?>
