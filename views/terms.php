<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cardikit Terms and Conditions">
    <meta name="keywords" content="Cardikit terms, terms of service, conditions, acceptable use">
    <meta name="theme-color" content="#fa3c25">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="https://cardikit.com/terms">

    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <link rel="apple-touch-icon" href="/assets/smaller-logo-no-background.png">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Terms & Conditions - Cardikit">
    <meta property="og:description" content="The rules and conditions for using Cardikit.">
    <meta property="og:url" content="https://cardikit.com/terms">
    <meta property="og:image" content="https://cardikit.com/assets/header-FA0IEdgE.webp">
    <meta property="og:image:alt" content="Cardikit terms">
    <meta property="og:site_name" content="Cardikit">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Terms & Conditions - Cardikit">
    <meta name="twitter:description" content="The rules and conditions for using Cardikit.">
    <meta name="twitter:image" content="https://cardikit.com/assets/hero-image-DloX0uJB.webp">

    <?php include __DIR__ . '/partials/analytics.php'; ?>
    <?php include __DIR__ . '/partials/cookie-consent.php'; ?>

    <title>Terms & Conditions - Cardikit</title>
    <link rel="stylesheet" href="/blog.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="nav">
                <a href="/" class="logo-container">
                    <img src="/assets/smaller-logo-no-background.png" alt="Cardikit logo" class="logo-image">
                    <span class="logo">Cardikit</span>
                </a>
                <button class="nav-toggle" aria-label="Toggle navigation">
                    <span class="hamburger"></span>
                </button>
                <ul class="nav-menu">
                    <li><a href="/#how-it-works" class="nav-link">How It Works</a></li>
                    <li><a href="/#features" class="nav-link">Features</a></li>
                    <li><a href="/blog" class="nav-link">Blog</a></li>
                    <li><a href="/#faq" class="nav-link">FAQ</a></li>
                    <li><a href="/app/register" class="btn btn-primary nav-cta">Get Started</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="blog-hero">
            <div class="container">
                <div class="breadcrumb">
                    <a href="/">Home</a>
                    <span class="breadcrumb-sep">→</span>
                    <span>Terms & Conditions</span>
                </div>
                <h1 class="blog-hero-title">Terms & <span class="highlight">Conditions</span></h1>
                <p class="blog-hero-subtitle">The rules for using Cardikit.</p>
            </div>
        </section>

        <section class="blog-content">
            <div class="container container-narrow">
                <article class="article-body">
                    <h2>1. Acceptance of Terms</h2>
                    <p>By using Cardikit, you agree to these Terms and Conditions. If you do not agree, please do not use the service.</p>

                    <h2>2. Your Account</h2>
                    <ul>
                        <li>You are responsible for maintaining the confidentiality of your login credentials.</li>
                        <li>You must provide accurate information when creating an account.</li>
                        <li>You must be at least 13 years old or the minimum age in your jurisdiction.</li>
                    </ul>

                    <h2>3. Acceptable Use</h2>
                    <ul>
                        <li>Do not upload or share illegal, harmful, or infringing content.</li>
                        <li>Do not attempt to disrupt or abuse the service.</li>
                        <li>Respect the privacy and rights of others.</li>
                    </ul>

                    <h2>4. Content Ownership</h2>
                    <p>You retain ownership of your content. You grant Cardikit a limited license to host and display content for the purpose of operating the service.</p>

                    <h2>5. Service Changes</h2>
                    <p>We may modify or discontinue features at any time. We will try to give notice when possible.</p>

                    <h2>6. Termination</h2>
                    <p>We may suspend or terminate accounts that violate these terms. You may delete your account at any time.</p>

                    <h2>7. Disclaimers</h2>
                    <p>Cardikit is provided “as is” without warranties of any kind. We do not guarantee uninterrupted or error-free service.</p>

                    <h2>8. Limitation of Liability</h2>
                    <p>To the fullest extent permitted by law, Cardikit is not liable for indirect, incidental, or consequential damages arising from your use of the service.</p>

                    <h2>9. Updates to Terms</h2>
                    <p>We may update these terms. If changes are material, we will provide notice. Continued use after updates constitutes acceptance.</p>

                    <h2>10. Contact</h2>
                    <p>Questions about these terms? Contact us at terms@cardikit.com.</p>
                </article>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <span class="logo">Cardikit</span>
                    <p class="footer-tagline">Your digital business card, reimagined.</p>
                </div>
                <div class="footer-links">
                    <a href="/blog">Blog</a>
                    <a href="/privacy">Privacy</a>
                    <a href="/terms">Terms</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 Cardikit. Open source & free forever.</p>
            </div>
        </div>
    </footer>

    <script>
        const navToggle = document.querySelector('.nav-toggle');
        const navMenu = document.querySelector('.nav-menu');
        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    </script>
</body>
</html>
