<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        $postTitle = $post['title'] ?? '';
        $postExcerpt = $post['excerpt'] ?? '';
        $postSlug = $post['slug'] ?? '';
        $postCategorySlug = $post['category_slug'] ?? '';
        $coverImage = $post['cover_image_url'] ?? 'https://cardikit.com/assets/header-FA0IEdgE.webp';
        $canonical = "https://cardikit.com/blog/{$postCategorySlug}/{$postSlug}";
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= esc($postExcerpt ?: 'Read the latest from the Cardikit blog.'); ?>">
    <meta name="keywords" content="Cardikit blog, digital business card, networking tips, <?= esc($post['category_name'] ?? ''); ?>">
    <meta name="theme-color" content="#fa3c25">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="<?= esc($canonical); ?>">

    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <link rel="apple-touch-icon" href="/assets/smaller-logo-no-background.png">

    <!-- Open Graph -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?= esc($postTitle); ?>">
    <meta property="og:description" content="<?= esc($postExcerpt ?: 'Read the latest from the Cardikit blog.'); ?>">
    <meta property="og:url" content="<?= esc($canonical); ?>">
    <meta property="og:image" content="<?= esc($coverImage); ?>">
    <meta property="og:image:alt" content="<?= esc($postTitle); ?>">
    <meta property="og:site_name" content="Cardikit">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($postTitle); ?>">
    <meta name="twitter:description" content="<?= esc($postExcerpt ?: 'Read the latest from the Cardikit blog.'); ?>">
    <meta name="twitter:image" content="<?= esc($coverImage); ?>">

    <!-- Structured data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BlogPosting",
      "headline": <?= json_encode($postTitle); ?>,
      "description": <?= json_encode($postExcerpt ?: 'Read the latest from the Cardikit blog.'); ?>,
      "image": [<?= json_encode($coverImage); ?>],
      "author": {
        "@type": "Person",
        "name": "Damion Voshall"
      },
      "publisher": {
        "@type": "Organization",
        "name": "Cardikit",
        "logo": {
          "@type": "ImageObject",
          "url": "https://cardikit.com/assets/smaller-logo-no-background.png"
        }
      },
      "url": <?= json_encode($canonical); ?>,
      "datePublished": <?= json_encode($post['published_at'] ?? $post['created_at'] ?? ''); ?>,
      "dateModified": <?= json_encode($post['updated_at'] ?? $post['published_at'] ?? $post['created_at'] ?? ''); ?>
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "https://cardikit.com/"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Blog",
          "item": "https://cardikit.com/blog"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": <?= json_encode($post['category_name'] ?? 'Category'); ?>,
          "item": <?= json_encode("https://cardikit.com/blog/{$postCategorySlug}"); ?>
        },
        {
          "@type": "ListItem",
          "position": 4,
          "name": <?= json_encode($postTitle); ?>,
          "item": <?= json_encode($canonical); ?>
        }
      ]
    }
    </script>
    <?php include __DIR__ . '/partials/analytics.php'; ?>
    <?php include __DIR__ . '/partials/cookie-consent.php'; ?>
    <title><?= esc($title ?? ''); ?> - Cardikit Blog</title>
    <link rel="stylesheet" href="<?= asset_url('/blog.css') ?>">
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
                    <div class="featured-image-wrapper" style="<?= $coverImage ? '' : 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);' ?>">
                        <?php if (!empty($coverImage)) : ?>
                            <img src="<?= esc($coverImage); ?>" alt="<?= esc($postTitle ?: 'Blog cover image'); ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
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
