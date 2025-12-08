<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage Cardikit blog posts.">
    <meta name="theme-color" content="#fa3c25">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <title>Blog Admin - Cardikit</title>
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
                    <span>Admin</span>
                </div>
                <div class="blog-hero-title">Manage <span class="highlight">Posts</span></div>
                <p class="blog-hero-subtitle">Quickly review and jump into editing.</p>
                <div style="margin-top: 1rem;">
                    <a href="/blog/create" class="btn btn-primary" style="margin-right: 0.5rem;">Create new post</a>
                    <a href="/blog/categories/admin" class="btn btn-secondary" style="margin-right: 0.5rem;">Manage categories</a>
                    <a href="/blog/images" class="btn btn-secondary">Manage images</a>
                </div>
            </div>
        </section>

        <section class="blog-content">
            <div class="container">
                <div class="section-title">All posts</div>
                <?php if (!empty($blogs)) : ?>
                    <div class="blog-grid">
                        <?php foreach ($blogs as $blog) : ?>
                            <article class="post-card">
                            <?php if ($blog['cover_image_url']) : ?>
                                <div style="background-image: url(<?= esc($blog['cover_image_url'] ?? ''); ?>); background-size: cover;" class="post-image">
                            <?php else : ?>
                                <div class="post-image">
                            <?php endif; ?>
                                    <span class="post-category-badge"><?= esc(($blog['status'] ?? 'draft') === 'published' ? 'Published' : 'Draft'); ?></span>
                                </div>
                                <div class="post-body">
                                    <div class="post-meta">
                                        <?php
                                            $dateValue = $blog['published_at'] ?? $blog['created_at'] ?? null;
                                            $formattedDate = $dateValue ? (new DateTime($dateValue))->format('F j, Y') : null;
                                        ?>
                                        <?php if ($formattedDate) : ?>
                                            <span class="post-date"><?= esc($formattedDate); ?></span>
                                        <?php endif; ?>
                                        <span class="post-read-time"><?= esc($blog['category_name'] ?? 'Uncategorized'); ?></span>
                                    </div>
                                    <h3 class="post-title"><?= esc($blog['title'] ?? 'Untitled'); ?></h3>
                                    <p class="post-excerpt"><?= esc($blog['excerpt'] ?? ''); ?></p>
                                    <div class="post-actions">
                                        <a href="/blog/<?= (int) ($blog['id'] ?? 0); ?>/edit" class="post-link">Edit →</a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p class="empty-state">No blog posts yet. Create your first one to see it here.</p>
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
