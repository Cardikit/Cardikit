<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage Cardikit blog categories.">
    <meta name="theme-color" content="#fa3c25">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <title>Categories Admin - Cardikit</title>
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
                    <li><a href="/blog" class="nav-link nav-link-active">Blog</a></li>
                    <li><a href="/#faq" class="nav-link">FAQ</a></li>
                    <li><a href="/app/register" class="btn btn-primary nav-cta">Create my free card</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="blog-hero">
            <div class="container">
                <div class="breadcrumb">
                    <a href="/blog">Blog</a>
                    <span class="breadcrumb-sep">→</span>
                    <span>Categories Admin</span>
                </div>
                <div class="blog-hero-title">Manage <span class="highlight">Categories</span></div>
                <p class="blog-hero-subtitle">Edit names, slugs, and descriptions.</p>
                <div style="margin-top: 1rem;">
                    <a href="/blog/admin" class="btn btn-secondary" style="margin-right: 0.5rem;">Back to posts</a>
                    <a href="/blog/categories/create" class="btn btn-primary">Create category</a>
                </div>
            </div>
        </section>

        <section class="blog-content">
            <div class="container">
                <div class="section-title">All categories</div>
                <?php if (!empty($categories)) : ?>
                    <div class="blog-grid">
                        <?php foreach ($categories as $category) : ?>
                            <article class="post-card">
                                <?php if ($category['image']) : ?>
                                    <div style="background-image: url(<?= esc($category['image'] ?? ''); ?>); background-size: cover;" class="post-image">
                                <?php else : ?>
                                    <div class="post-image">
                                <?php endif; ?>
                                    <span class="post-category-badge">Posts: <?= esc((int) ($category['post_count'] ?? 0)); ?></span>
                                </div>
                                <div class="post-body">
                                    <div class="post-meta">
                                        <span class="post-read-time">Slug: <?= esc($category['slug'] ?? ''); ?></span>
                                    </div>
                                    <h3 class="post-title"><?= esc($category['name'] ?? ''); ?></h3>
                                    <p class="post-excerpt"><?= esc($category['description'] ?? ''); ?></p>
                                    <div class="post-actions">
                                        <a href="/blog/categories/<?= (int) ($category['id'] ?? 0); ?>/edit" class="post-link">Edit →</a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p class="empty-state">No categories yet. Create one to get started.</p>
                <?php endif; ?>
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
