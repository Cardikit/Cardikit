<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="10 Tips for Making Lasting Connections at Networking Events - Learn how to network effectively.">
    <meta name="theme-color" content="#fa3c25">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <title><?= esc($title ?? ''); ?> - Cardikit Blog</title>
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
        <!-- Article Header -->
        <article class="article">
            <header class="article-header">
                <div class="container container-narrow">
                    <div class="breadcrumb">
                        <a href="/blog">Blog</a>
                        <span class="breadcrumb-sep">→</span>
                            <a href="/blog/<?= esc($post['category_slug'] ?? ''); ?>"><?= esc($post['category_name'] ?? ''); ?></a>
                        <span class="breadcrumb-sep">→</span>
                        <span>Article</span>
                    </div>
                    <span class="article-category"><?= esc($post['category_name'] ?? ''); ?></span>
                    <h1 class="article-title"><?= esc($post['title'] ?? ''); ?></h1>
                    <p class="article-subtitle"><?= esc($post['excerpt'] ?? ''); ?></p>
                    <div class="article-meta">
                        <div class="article-author">
                            <div class="author-avatar">DV</div>
                            <div class="author-info">
                                <span class="author-name">Damion Voshall</span>
                                <span class="author-role">Software Engineer</span>
                            </div>
                        </div>
                        <div class="article-details">
                            <?php
                                $dateValue = $post['published_at'] ?? $post['created_at'] ?? null;
                                $formattedDate = $dateValue ? (new DateTime($dateValue))->format('F j, Y') : null;
                            ?>
                            <?php if ($formattedDate) : ?>
                                <span class="article-date"><?= esc($formattedDate); ?></span>
                            <?php endif; ?>
                            <span class="article-read-time"><?= esc((int) ($post['read_time_minutes'] ?? 5)); ?> min read</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            <div class="article-featured-image">
                <div class="container">
                    <?php if ($post['cover_image_url']) : ?>
                        <div style="background-image: url(<?= esc($post['cover_image_url'] ?? ''); ?>); background-size: cover;" class="featured-image-wrapper">
                    <?php else : ?>
                        <div class="featured-image-wrapper" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Article Content -->
            <div class="article-content">
                <div class="container container-narrow">
                    <div class="article-body">
                        <?= $contentHtml ?? ''; ?>
                    </div>
                </div>
            </div>

            <!-- Article Footer -->
            <footer class="article-footer">
                <div class="container container-narrow">
                    <!--
                    <div class="article-tags">
                        <span class="tag">Networking</span>
                        <span class="tag">Professional Development</span>
                        <span class="tag">Career Tips</span>
                    </div>
                    -->
                    <div class="article-share">
                        <span class="share-label">Share this article:</span>
                        <div class="share-buttons">
                            <a href="#" class="share-btn">Twitter</a>
                            <a href="#" class="share-btn">LinkedIn</a>
                            <a href="#" class="share-btn">Facebook</a>
                        </div>
                    </div>
                </div>
            </footer>
        </article>

        <!-- Related Posts -->
        <section class="related-posts">
            <div class="container">
                <h2 class="section-title">Related Articles</h2>
                <div class="related-grid">
                    <?php foreach ($recentPosts as $recentPost) : ?>
                    <article class="related-card">
                        <?php if ($recentPost['cover_image_url']) : ?>
                            <div style="background-image: url(<?= esc($recentPost['cover_image_url'] ?? ''); ?>); background-size: cover;" class="related-image"></div>
                        <?php else : ?>
                            <div class="related-image" style="background: linear-gradient(135deg, #FA3C25 0%, #ff6b5b 100%);"></div>
                        <?php endif; ?>
                        <div class="related-body">
                            <span class="related-category"><?= esc($recentPost['category_name'] ?? ''); ?></span>
                            <h3 class="related-title">
                                <a href="/blog/<?= esc($recentPost['category_slug'] ?? '') . '/' . esc($recentPost['slug'] ?? ''); ?>"><?= esc($recentPost['title'] ?? ''); ?></a>
                            </h3>
                            <?php
                                $recentDateVal = $recentPost['published_at'] ?? $recentPost['created_at'] ?? null;
                                $recentFormattedDate = $recentDateVal ? (new DateTime($recentDateVal))->format('F j, Y') : null;
                            ?>
                            <?php if ($recentFormattedDate) : ?>
                                <span class="related-date"><?= esc($recentFormattedDate); ?></span>
                            <?php endif; ?>
                        </div>
                    </article>
                    <?php endforeach; ?>
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
