<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cardikit Blog - Tips, guides and news about digital business cards, networking, and personal branding.">
    <meta name="keywords" content="digital business card blog, networking tips, personal branding, Cardikit">
    <meta name="theme-color" content="#fa3c25">
    <link rel="canonical" href="https://cardikit.com/blog">

    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <link rel="apple-touch-icon" href="/assets/smaller-logo-no-background.png">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Cardikit Blog - Digital Business Card Tips & News">
    <meta property="og:description" content="Tips, guides and news about digital business cards, networking, and personal branding.">
    <meta property="og:url" content="https://cardikit.com/blog">
    <meta property="og:image" content="https://cardikit.com/assets/header-FA0IEdgE.webp">
    <meta property="og:image:alt" content="Cardikit blog header graphic">
    <meta property="og:site_name" content="Cardikit">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Cardikit Blog - Digital Business Card Tips & News">
    <meta name="twitter:description" content="Tips, guides and news about digital business cards, networking, and personal branding.">
    <meta name="twitter:image" content="https://cardikit.com/assets/hero-image-DloX0uJB.webp">

    <!-- Structured data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "CollectionPage",
      "name": "Cardikit Blog",
      "description": "Tips, guides and news about digital business cards, networking, and personal branding.",
      "url": "https://cardikit.com/blog",
      "image": "https://cardikit.com/assets/header-FA0IEdgE.webp",
      "publisher": {
        "@type": "Organization",
        "name": "Cardikit",
        "logo": {
          "@type": "ImageObject",
          "url": "https://cardikit.com/assets/smaller-logo-no-background.png"
        }
      }
    }
    </script>

    <?php include __DIR__ . '/partials/analytics.php'; ?>
    <?php include __DIR__ . '/partials/cookie-consent.php'; ?>
    <title>Blog - Cardikit | Digital Business Card Tips & News</title>
    <link rel="stylesheet" href="/blog.css">
</head>
<body>
    <!-- Navigation -->
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
                    <li><a href="/blog" class="nav-link nav-link-active">Blog</a></li>
                    <li><a href="/#faq" class="nav-link">FAQ</a></li>
                    <li><a href="/app/register" class="btn btn-primary nav-cta">Create my free card</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <!-- Blog Hero -->
        <section class="blog-hero">
            <div class="container">
                <h1 class="blog-hero-title">The Cardikit <span class="highlight">Blog</span></h1>
                <p class="blog-hero-subtitle">Tips, guides, and insights on digital networking and personal branding.</p>
            </div>
        </section>

        <!-- Blog Content -->
        <section class="blog-content">
            <div class="container blog-grid">
                <!-- Main Posts Column -->
                <div class="blog-main">
                    <h2 class="section-title">Latest Posts</h2>

                    <!-- Posts -->
                    <?php foreach ($posts as $post) : ?>
                        <article class="post-card">
                            <?php if ($post['cover_image_url']) : ?>
                                <div style="background-image: url(<?= esc($post['cover_image_url'] ?? ''); ?>); background-size: cover;" class="post-image">
                            <?php else : ?>
                                <div class="post-image">
                            <?php endif; ?>
                                <span class="post-category-badge"><?= esc($post['category_name'] ?? ''); ?></span>
                            </div>
                            <div class="post-body">
                                <div class="post-meta">
                                    <?php
                                        $dateValue = $post['published_at'] ?? $post['created_at'] ?? null;
                                        $formattedDate = $dateValue ? (new DateTime($dateValue))->format('F j, Y') : null;
                                    ?>
                                    <?php if ($formattedDate) : ?>
                                        <span class="post-date"><?= esc($formattedDate); ?></span>
                                    <?php endif; ?>
                                    <span class="post-read-time"><?= esc((int) ($post['read_time_minutes'] ?? 5)); ?> min read</span>
                                </div>
                                <h3 class="post-title">
                                    <a href="/blog/<?= esc($post['category_slug'] ?? '') . '/' . esc($post['slug'] ?? ''); ?>"><?= esc($post['title'] ?? ''); ?></a>
                                </h3>
                                <p class="post-excerpt"><?= esc($post['excerpt'] ?? ''); ?></p>
                                <a href="/blog/<?= esc($post['category_slug'] ?? '') . '/' . esc($post['slug'] ?? ''); ?>" class="post-link">Read more →</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                    <!-- /Posts -->
                </div>

                <!-- Sidebar -->
                <aside class="blog-sidebar">
                    <!-- Categories -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title">Categories</h3>
                        <ul class="category-list">
                            <!-- Category items -->
                            <?php foreach ($categories as $category) : ?>
                            <li class="category-item">
                                <a href="/blog/<?= esc($category['slug'] ?? ''); ?>">
                                    <span class="category-name"><?= esc($category['name'] ?? ''); ?></span>
                                    <span class="category-count"><?= esc((int) ($category['post_count'] ?? 0)); ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                            <!-- !Category items -->
                            <li class="category-item">
                                <a href="/blog/categories">
                                    <span style="text-decoration: underline;" class="category-name">See more...</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Newsletter -->
                    <div class="sidebar-widget newsletter-widget">
                        <h3 class="widget-title">Stay Updated</h3>
                        <p class="newsletter-text">Get the latest tips and news delivered to your inbox.</p>
                        <form class="newsletter-form">
                            <input type="email" class="newsletter-input" placeholder="Enter your email" required>
                            <button type="submit" class="btn btn-primary newsletter-btn">Subscribe</button>
                        </form>
                    </div>

                    <!-- CTA Widget -->
                    <div class="sidebar-widget cta-widget">
                        <h3 class="cta-title">Ready to go digital?</h3>
                        <p class="cta-text">Create your free digital business card in minutes.</p>
                        <a href="/app/register" class="btn btn-primary btn-full">Get Started Free</a>
                    </div>
                </aside>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <span class="logo">Cardikit</span>
                    <p class="footer-tagline">Your digital business card, reimagined.</p>
                </div>
                <div class="footer-links">
                    <a href="/#how-it-works">How It Works</a>
                    <a href="/#features">Features</a>
                    <a href="/blog">Blog</a>
                    <a href="/#faq">FAQ</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2024 Cardikit. Open source & free forever.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile navigation toggle
        const navToggle = document.querySelector('.nav-toggle');
        const navMenu = document.querySelector('.nav-menu');
        
        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    </script>
</body>
</html>
