<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Browse all Cardikit blog categories - Networking, Digital Cards, Personal Branding, and more.">
    <meta name="theme-color" content="#fa3c25">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <title>Categories - Cardikit Blog</title>
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
                    <li><a href="/app/register" class="btn btn-primary nav-cta">Get Started</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <!-- Page Hero -->
        <section class="blog-hero">
            <div class="container">
                <div class="breadcrumb">
                    <a href="/blog">Blog</a>
                    <span class="breadcrumb-sep">→</span>
                    <span>Categories</span>
                </div>
                <h1 class="blog-hero-title">All <span class="highlight">Categories</span></h1>
                <p class="blog-hero-subtitle">Explore our content organized by topic.</p>
            </div>
        </section>

        <!-- Categories Grid -->
        <section class="blog-content">
            <div class="container">
                <div class="categories-grid">

                    <!-- Category Items -->
                    <?php if (!empty($categories)) : ?>
                        <?php foreach ($categories as $category): ?>
                        <a href="/blog/<?= esc($category['slug'] ?? ''); ?>" class="category-card">
                            <?php if ($category['image']) : ?>
                            <div style="background-image: url(<?= esc($category['image'] ?? ''); ?>); background-size: cover;" class="category-card-icon"></div>
                            <?php else : ?>
                            <div class="category-card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <?php endif; ?>
                            <h3 class="category-card-title"><?= esc($category['name'] ?? ''); ?></h3>
                            <p class="category-card-desc"><?= esc($category['description'] ?? ''); ?></p>
                            <?php $count = (int) ($category['post_count'] ?? 0); ?>
                            <span class="category-card-count"><?= $count; ?> <?= $count === 1 ? 'article' : 'articles'; ?></span>
                        </a>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="empty-state">No categories found.</p>
                    <?php endif; ?>
                    <!-- /Category Items -->

                </div>
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
        const navToggle = document.querySelector('.nav-toggle');
        const navMenu = document.querySelector('.nav-menu');
        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    </script>
</body>
</html>
