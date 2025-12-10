<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cardikit - Create a free digital business card you can share with a tap, QR code, or link. Never run out of business cards again.">
    <meta name="keywords" content="digital business card, QR code card, NFC card, contact sharing, Cardikit">
    <meta name="theme-color" content="#fa3c25">
    <link rel="canonical" href="https://cardikit.com/">

    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <link rel="apple-touch-icon" href="/assets/smaller-logo-no-background.png">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Cardikit - Open Source Digital Business Card Maker">
    <meta property="og:description" content="Create and share digital business cards with QR codes and NFC-ready links.">
    <meta property="og:url" content="https://cardikit.com/">
    <meta property="og:image" content="https://cardikit.com/assets/header-FA0IEdgE.webp">
    <meta property="og:image:alt" content="Cardikit header graphic">
    <meta property="og:site_name" content="Cardikit">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Cardikit - Open Source Digital Business Card Maker">
    <meta name="twitter:description" content="Create and share digital business cards with QR codes and NFC-ready links.">
    <meta name="twitter:image" content="https://cardikit.com/assets/hero-image-DloX0uJB.webp">

    <!-- Structured data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Cardikit",
      "url": "https://cardikit.com",
      "logo": "https://cardikit.com/assets/smaller-logo-no-background.png",
      "description": "Create and share digital business cards with QR codes and NFC-ready links.",
      "sameAs": [
        "https://cardikit.com",
        "https://cardikit.com/app"
      ]
    }
    </script>
    <?php include __DIR__ . '/partials/analytics.php'; ?>

    <title>Cardikit - Open Source Digital Business Card Maker</title>
    <link rel="stylesheet" href="landing.css">
    <?php include __DIR__ . '/partials/analytics.php'; ?>
    <?php include __DIR__ . '/partials/cookie-consent.php'; ?>
</head>
<body>
    <!-- Navigation -->
    <header class="header">
        <nav class="nav container">
            <a href="/" class="logo-container" aria-label="Cardikit home">
                <img src="/assets/smaller-logo-no-background.png" alt="Cardikit logo" class="logo-image">
                <div class="logo-wordmark">
                    <span class="logo">Cardikit</span>
                </div>
            </a>

            <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
                <span class="hamburger"></span>
            </button>

            <ul class="nav-menu">
                <li><a href="/#features" class="nav-link">Product</a></li>
                <li><a href="/#pricing" class="nav-link">Pricing</a></li>
                <li><a href="/#faq" class="nav-link">FAQ</a></li>
                <li><a href="/blog" class="nav-link">Blog</a></li>
                <li><a href="/app/register" class="btn btn-primary nav-cta">Create my free card</a></li>
                <li><a href="/app/login" class="btn btn-outline nav-cta">Log in</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container hero-grid">
                <div class="hero-content">
                    <h1 class="hero-headline">Your Personal Brand,<br><span class="hero-highlight">Upgraded.</span></h1>
                    <p class="hero-subheadline">Cardikit lets you create a free digital business card you can share with a tap, QR code, or link. Update it in seconds, and make sure every connection always has your latest details.</p>
                    <div class="hero-cta">
                        <a href="/app/register" class="btn btn-primary btn-large">Create my free card</a>
                        <a href="#use-cases" class="hero-secondary-link">See a sample card</a>
                    </div>
                    <p class="hero-reassurance">Free to start. No credit card required. Open source.</p>
                </div>
                <div class="hero-visual">
                    <div class="phone-mockup">
                        <div class="phone-frame">
                            <div class="digital-card">
                                <div class="card-avatar">JD</div>
                                <h3 class="card-name">Jane Doe</h3>
                                <p class="card-title">Product Designer</p>
                                <p class="card-company">Creative Studio</p>
                                <div class="card-divider"></div>
                                <div class="card-contact">
                                    <div class="contact-item">
                                        <span class="contact-icon">📧</span>
                                        <span>jane@example.com</span>
                                    </div>
                                    <div class="contact-item">
                                        <span class="contact-icon">📱</span>
                                        <span>+1 (555) 123-4567</span>
                                    </div>
                                    <div class="contact-item">
                                        <span class="contact-icon">🌐</span>
                                        <span>janedoe.design</span>
                                    </div>
                                </div>
                                <div class="card-socials">
                                    <span class="social-icon">in</span>
                                    <span class="social-icon">𝕏</span>
                                    <span class="social-icon">◉</span>
                                </div>
                                <div class="card-qr">
                                    <div class="qr-placeholder"></div>
                                    <span class="qr-label">Scan to save</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Social Proof Strip -->
        <section class="social-proof">
            <div class="container">
                <p class="social-proof-label">Trusted by freelancers, agents, and small business owners.</p>
                <div class="proof-badges">
                    <div class="proof-badge">
                        <span class="badge-icon">⚡</span>
                        <span class="badge-text">Instant updates</span>
                    </div>
                    <div class="proof-badge">
                        <span class="badge-icon">📈</span>
                        <span class="badge-text">2x more saved contacts</span>
                    </div>
                    <div class="proof-badge">
                        <span class="badge-icon">🌍</span>
                        <span class="badge-text">Works worldwide</span>
                    </div>
                    <div class="proof-badge">
                        <span class="badge-icon">♻️</span>
                        <span class="badge-text">Eco-friendly</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="how-it-works">
            <div class="container">
                <h2 class="section-heading">How Cardikit works</h2>
                <p class="section-intro">Get your digital business card live in just a few minutes.</p>
                <div class="steps-grid">
                    <div class="step-card">
                        <span class="step-icon">✏️</span>
                        <span class="step-number">1</span>
                        <h3 class="step-title">Create your card</h3>
                        <p class="step-description">Enter your name, role, contact info, and links—everything you'd put on a modern business card.</p>
                    </div>
                    <div class="step-card">
                        <span class="step-icon">📲</span>
                        <span class="step-number">2</span>
                        <h3 class="step-title">Share with a tap or QR</h3>
                        <p class="step-description">Show your QR code or share your unique link so new contacts can save you instantly.</p>
                    </div>
                    <div class="step-card">
                        <span class="step-icon">🔄</span>
                        <span class="step-number">3</span>
                        <h3 class="step-title">Update anytime</h3>
                        <p class="step-description">Change jobs? New number? Update your card once and every saved link stays in sync.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="features">
            <div class="container">
                <h2 class="section-heading">Why professionals switch to Cardikit</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <span class="feature-icon">🔄</span>
                        <h3 class="feature-title">Always up to date</h3>
                        <p class="feature-description">Update your details once and every future connection sees the latest version.</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">📤</span>
                        <h3 class="feature-title">Share anywhere</h3>
                        <p class="feature-description">Share via QR code, link, or NFC card</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">🔗</span>
                        <h3 class="feature-title">All your links in one place</h3>
                        <p class="feature-description">Add your website, portfolio, socials, booking links, and more.</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">📱</span>
                        <h3 class="feature-title">Designed to look great on any device</h3>
                        <p class="feature-description">Your card is fast, clean, and looks sharp on phones and desktops.</p>
                    </div>
                </div>
                <div class="features-cta">
                    <p>Ready to build yours?</p>
                    <a href="/app/register" class="btn btn-primary">Create my free card</a>
                </div>
            </div>
        </section>

        <!-- Use Cases Section -->
        <section id="use-cases" class="use-cases">
            <div class="container">
                <h2 class="section-heading">Built for modern networking</h2>
                <p class="section-intro">Whether you're closing deals, meeting clients, or growing a personal brand, Cardikit keeps your details one tap away.</p>
                <div class="use-cases-grid">
                    <div class="use-case-card">
                        <span class="use-case-icon">🎨</span>
                        <h3 class="use-case-title">Freelancers & creators</h3>
                        <p class="use-case-description">Show off your portfolio, socials, and booking links in one shareable card.</p>
                    </div>
                    <div class="use-case-card">
                        <span class="use-case-icon">💼</span>
                        <h3 class="use-case-title">Sales & real estate</h3>
                        <p class="use-case-description">Help prospects save your info instantly and follow up while you're still top-of-mind.</p>
                    </div>
                    <div class="use-case-card">
                        <span class="use-case-icon">🏪</span>
                        <h3 class="use-case-title">Small business owners</h3>
                        <p class="use-case-description">Give every customer an easy way to contact you, leave reviews, or browse your website.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Comparison Section -->
        <section class="comparison">
            <div class="container">
                <h2 class="section-heading">Digital vs. paper business cards</h2>
                <div class="comparison-grid">
                    <div class="comparison-card comparison-paper">
                        <h3 class="comparison-title">Paper cards</h3>
                        <ul class="comparison-list">
                            <li class="comparison-item negative">
                                <span class="comparison-icon">✗</span>
                                Easy to lose
                            </li>
                            <li class="comparison-item negative">
                                <span class="comparison-icon">✗</span>
                                Out of date quickly
                            </li>
                            <li class="comparison-item negative">
                                <span class="comparison-icon">✗</span>
                                Need reprinting
                            </li>
                            <li class="comparison-item negative">
                                <span class="comparison-icon">✗</span>
                                Not clickable
                            </li>
                            <li class="comparison-item negative">
                                <span class="comparison-icon">✗</span>
                                End up in the trash
                            </li>
                        </ul>
                    </div>
                    <div class="comparison-card comparison-digital">
                        <h3 class="comparison-title">Cardikit digital card</h3>
                        <ul class="comparison-list">
                            <li class="comparison-item positive">
                                <span class="comparison-icon">✓</span>
                                Always with you
                            </li>
                            <li class="comparison-item positive">
                                <span class="comparison-icon">✓</span>
                                Update once, share everywhere
                            </li>
                            <li class="comparison-item positive">
                                <span class="comparison-icon">✓</span>
                                No printing costs
                            </li>
                            <li class="comparison-item positive">
                                <span class="comparison-icon">✓</span>
                                Clickable links & QR
                            </li>
                            <li class="comparison-item positive">
                                <span class="comparison-icon">✓</span>
                                More eco-friendly
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="comparison-cta">
                    <p>Skip the stack of paper cards. Create your free digital card today.</p>
                    <a href="/app/register" class="btn btn-outline">Get started free</a>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="pricing">
            <div class="container">
                <h2 class="section-heading">Simple, transparent pricing</h2>
                <p class="section-intro">Start free and upgrade when you need more. All plans include unlimited updates and sharing.</p>

                <div class="pricing-toggle">
                    <span class="toggle-label active" id="monthly-label">Monthly</span>
                    <div class="toggle-switch" id="billing-toggle">
                        <div class="toggle-slider"></div>
                    </div>
                    <span class="toggle-label" id="annual-label">
                        Annual
                        <span class="pricing-badge">Save 20%</span>
                    </span>
                </div>

                <div class="pricing-grid">
                    <!-- Free Plan -->
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3 class="pricing-name">Free</h3>
                            <p class="pricing-description">Perfect for getting started</p>
                        </div>
                        <div class="pricing-price">
                            <span class="price-currency">$</span>
                            <span class="price-amount">0</span>
                        </div>
                        <p class="pricing-billing">Forever free</p>
                        <ul class="pricing-features">
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>4 digital business cards</span>
                            </li>
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>Unlimited updates</span>
                            </li>
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>Standard QR</span>
                            </li>
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>Basic themes</span>
                            </li>
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>Basic sharing</span>
                            </li>
                        </ul>
                        <div class="pricing-cta">
                            <a href="/app/register" class="btn btn-outline" style="width: 100%;">Get started free</a>
                        </div>
                    </div>

                    <!-- Pro Plan -->
                    <div class="pricing-card featured">
                        <span class="pricing-badge-top">Most Popular</span>
                        <div class="pricing-header">
                            <h3 class="pricing-name">Pro</h3>
                            <p class="pricing-description">For professionals who network</p>
                        </div>
                        <div class="pricing-price">
                            <span class="price-currency">$</span>
                            <span class="price-amount" id="pro-price">9</span>
                            <span class="price-period">/month</span>
                        </div>
                        <p class="pricing-billing" id="pro-billing">Billed monthly</p>
                        <ul class="pricing-features">
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span><strong>Everything in Free, plus:</strong></span>
                            </li>
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>Unlimited cards</span>
                            </li>
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>Premium themes</span>
                            </li>
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>Analytics & insights</span>
                            </li>
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>Lead capture forms</span>
                            </li>
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>Custom QR</span>
                            </li>
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>Priority support</span>
                            </li>
                        </ul>
                        <div class="pricing-cta">
                            <a href="/app/register" class="btn btn-primary" style="width: 100%;">Start Pro trial</a>
                        </div>
                    </div>

                    <!-- Business Plan -->
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3 class="pricing-name">Enterprise</h3>
                            <p class="pricing-description">For teams and organizations</p>
                        </div>
                        <div class="pricing-price">
                            <span class="price-amount">Contact</span>
                        </div>
                        <p class="pricing-billing" id="business-billing">For pricing</p>
                        <ul class="pricing-features">
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span><strong>Everything in Pro, plus:</strong></span>
                            </li>
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>Custom themes</span>
                            </li>
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>Custom integrations</span>
                            </li>
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>Dedicated onboarding</span>
                            </li>
                            <li class="pricing-feature">
                                <span class="pricing-feature-icon">✓</span>
                                <span>Dedicated support</span>
                            </li>
                        </ul>
                        <div class="pricing-cta">
                            <a href="/app/register" class="btn btn-outline" style="width: 100%;">Contact sales</a>
                        </div>
                    </div>
                </div>

                <div class="pricing-footer">
                    <p>Pro plan includes a 14-day free trial. No credit card required.</p>
                    <p>Questions? <a href="mailto:contact@cardikit.com">Contact us</a> or check our <a href="#faq">FAQ</a></p>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="faq">
            <div class="container">
                <h2 class="section-heading">Frequently asked questions</h2>
                <div class="faq-list">
                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span>Is Cardikit really free?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Yes! It's completely free to create and share your digital business card. We do offer premium features such as custom themes, but your free card will always remain free.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span>Do I need an app to share my card?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>No app needed! Your card lives on the web. Just share it with a QR code or send your unique link via text, email, or any messaging app.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span>Can I update my card after I share it?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Absolutely! That's the beauty of a digital card. Make changes anytime and anyone with your link will automatically see your latest information.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span>Does my card work on iPhone and Android?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Yes! Your Cardikit card works on any modern smartphone, tablet, or computer with a web browser. No special app required to view or save it.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span>Can I create more than one card?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Yes, you can create multiple cards for different roles or brands. Perfect if you have a side business or want separate cards for personal and professional networking.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span>How do people save my card to their contacts?</span>
                            <span class="faq-icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>When someone opens your card, they can tap a button to download your contact info directly to their phone's address book. It's that simple!</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA Section -->
        <section class="final-cta">
            <div class="container">
                <h2 class="final-cta-heading">Ready to replace your paper cards?</h2>
                <p class="final-cta-text">Create your free digital business card in minutes and make sure every introduction sticks.</p>
                <a href="/app/register" class="btn btn-primary btn-large">Create my free card</a>
                <p class="final-cta-note">Takes about 2 minutes. No credit card required.</p>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-content">
            <p class="footer-copyright">&copy; <span id="current-year"></span> Cardikit</p>
            <nav class="footer-nav">
                <a href="/privacy" class="footer-link">Privacy</a>
                <a href="/terms" class="footer-link">Terms</a>
                <a href="mailto:contact@cardikit.com" class="footer-link">Contact</a>
            </nav>
        </div>
    </footer>

    <script src="landing.js"></script>
</body>
</html>
