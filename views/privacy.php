<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cardikit Privacy Policy">
    <meta name="keywords" content="Cardikit privacy policy, data protection, GDPR, CCPA">
    <meta name="theme-color" content="#fa3c25">
    <link rel="canonical" href="https://cardikit.com/privacy">

    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <link rel="apple-touch-icon" href="/assets/smaller-logo-no-background.png">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Privacy Policy - Cardikit">
    <meta property="og:description" content="How Cardikit collects, uses, and protects your data.">
    <meta property="og:url" content="https://cardikit.com/privacy">
    <meta property="og:image" content="https://cardikit.com/assets/header-FA0IEdgE.webp">
    <meta property="og:image:alt" content="Cardikit privacy">
    <meta property="og:site_name" content="Cardikit">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Privacy Policy - Cardikit">
    <meta name="twitter:description" content="How Cardikit collects, uses, and protects your data.">
    <meta name="twitter:image" content="https://cardikit.com/assets/hero-image-DloX0uJB.webp">

    <?php include __DIR__ . '/partials/analytics.php'; ?>
    <?php include __DIR__ . '/partials/cookie-consent.php'; ?>

    <title>Privacy Policy - Cardikit</title>
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
                    <span>Privacy Policy</span>
                </div>
                <h1 class="blog-hero-title">Privacy <span class="highlight">Policy</span></h1>
                <p class="blog-hero-subtitle">How we collect, use, and protect your data.</p>
            </div>
        </section>

        <section class="blog-content">
            <div class="container container-narrow">
                <article class="article-body">
                    <h2>1. Overview</h2>
                    <p>Cardikit is committed to protecting your privacy. This policy explains what data we collect, why, and how we safeguard it.</p>

                    <h2>2. Data We Collect</h2>
                    <ul>
                        <li>Account data: name, email, and credentials you provide when registering.</li>
                        <li>Content data: cards, images, blog posts, and metadata you upload or create.</li>
                        <li>Usage data: device/browser info, IP address, and interactions for security and analytics.</li>
                    </ul>

                    <h2>3. How We Use Data</h2>
                    <ul>
                        <li>To provide and improve Cardikit features.</li>
                        <li>To secure accounts, prevent abuse, and debug issues.</li>
                        <li>To send service-related communications (e.g., account changes). Marketing emails are opt-in.</li>
                    </ul>

                    <h2>4. Cookies & Analytics</h2>
                    <p>We use cookies for essential functionality and analytics (Google Analytics). You can manage consent via the cookie banner.</p>

                    <h2>5. Sharing</h2>
                    <p>We do not sell your data. We share only with service providers required to operate Cardikit (e.g., hosting, analytics) under appropriate safeguards.</p>

                    <h2>6. Data Retention</h2>
                    <p>We retain data while your account is active or as needed for legal/security requirements. You may request deletion of your account and associated data.</p>

                    <h2>7. Security</h2>
                    <p>We use TLS, access controls, and industry-standard protections. No system is 100% secure; please use a strong password and protect your credentials.</p>

                    <h2>8. Your Rights</h2>
                    <p>Depending on your region, you may have rights to access, correct, export, or delete your data. Contact us to exercise these rights.</p>

                    <h2>9. Contact</h2>
                    <p>Questions? Reach us at privacy@cardikit.com.</p>
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
