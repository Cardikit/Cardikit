<?php
    $cookieConsentKey = 'cookieConsent';
?>
<style>
    .cookie-banner {
        position: fixed;
        bottom: 16px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        max-width: 960px;
        width: calc(100% - 32px);
        background: #111827;
        color: #f9fafb;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        display: none;
        gap: 12px;
        align-items: center;
    }
    .cookie-banner__text {
        font-size: 14px;
        line-height: 1.5;
        margin: 0;
        flex: 1;
    }
    .cookie-banner__actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .cookie-banner__button {
        border: none;
        cursor: pointer;
        font-size: 14px;
        border-radius: 10px;
        padding: 10px 14px;
    }
    .cookie-banner__button--accept {
        background: #fa3c25;
        color: #fff;
    }
    .cookie-banner__button--learn {
        background: #1f2937;
        color: #e5e7eb;
        border: 1px solid #374151;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    @media (max-width: 640px) {
        .cookie-banner {
            flex-direction: column;
            align-items: flex-start;
        }
        .cookie-banner__actions {
            width: 100%;
        }
        .cookie-banner__button {
            width: 100%;
            text-align: center;
        }
    }
</style>
<div class="cookie-banner" id="cookieBanner" role="dialog" aria-live="polite" aria-label="Cookie notice">
    <p class="cookie-banner__text">
        We use cookies and similar technologies to improve your experience, personalize content, and analyze traffic. By clicking “Accept”, you consent to their use.
    </p>
    <div class="cookie-banner__actions">
        <a class="cookie-banner__button cookie-banner__button--learn" href="/privacy" aria-label="Learn more about our privacy policy">Learn more</a>
        <button class="cookie-banner__button cookie-banner__button--accept" id="cookieAcceptButton" type="button">Accept</button>
    </div>
</div>
<script>
    (() => {
        const banner = document.getElementById('cookieBanner');
        const acceptBtn = document.getElementById('cookieAcceptButton');
        const storageKey = '<?= $cookieConsentKey; ?>';

        const hasConsent = localStorage.getItem(storageKey) === 'true';
        if (!hasConsent && banner) {
            banner.style.display = 'flex';
        }

        acceptBtn?.addEventListener('click', () => {
            localStorage.setItem(storageKey, 'true');
            if (banner) {
                banner.style.display = 'none';
            }
        });
    })();
</script>
