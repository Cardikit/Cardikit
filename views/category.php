<?php
    $rawCategoryName = $category['name'] ?? 'Category';
    $categoryName = esc($rawCategoryName);
    $description = esc($category['description'] ?? "Explore {$rawCategoryName} articles.");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        $coverImage = $category['image'] ?? 'https://cardikit.com/assets/header-FA0IEdgE.webp';
        $canonical = 'https://cardikit.com/blog/' . ($category['slug'] ?? '');
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $description; ?>">
    <meta name="keywords" content="Cardikit, blog category, <?= $categoryName; ?>">
    <meta name="theme-color" content="#fa3c25">
    <link rel="canonical" href="<?= esc($canonical); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <link rel="apple-touch-icon" href="/assets/smaller-logo-no-background.png">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= $categoryName; ?> - Cardikit Blog">
    <meta property="og:description" content="<?= $description; ?>">
    <meta property="og:url" content="<?= esc($canonical); ?>">
    <meta property="og:image" content="<?= esc($coverImage); ?>">
    <meta property="og:image:alt" content="<?= $categoryName; ?>">
    <meta property="og:site_name" content="Cardikit">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $categoryName; ?> - Cardikit Blog">
    <meta name="twitter:description" content="<?= $description; ?>">
    <meta name="twitter:image" content="<?= esc($coverImage); ?>">

    <!-- Structured data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "CollectionPage",
      "name": <?= json_encode($categoryName); ?>,
      "description": <?= json_encode($description); ?>,
      "url": <?= json_encode($canonical); ?>,
      "image": <?= json_encode($coverImage); ?>,
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
    <title><?= $categoryName; ?> - Cardikit Blog</title>
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
        <!-- Category Hero -->
        <section class="category-hero">
            <div class="container">
                <div class="breadcrumb">
                    <a href="/blog">Blog</a>
                    <span class="breadcrumb-sep">→</span>
                    <a href="/blog/categories">Categories</a>
                    <span class="breadcrumb-sep">→</span>
                    <span><?= $categoryName; ?></span>
                </div>
                <div class="category-hero-content">
                    <?php if ($category['image']) : ?>
                        <div class="category-hero-icon" style="background-image: url('<?= $category['image']; ?>'); background-size: cover;"></div>
                    <?php else : ?>
                        <div class="category-hero-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h1 class="category-hero-title"><?= $categoryName; ?></h1>
                        <p class="category-hero-desc"><?= $description; ?></p>
                        <span class="category-hero-count">
                            <?= (int) ($totalPosts ?? 0); ?> <?= ($totalPosts ?? 0) === 1 ? 'article' : 'articles'; ?>
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Posts List -->
        <section class="blog-content">
            <div class="container">
                <div class="posts-list">
                    <?php if (!empty($posts)) : ?>
                        <?php foreach ($posts as $post) : ?>
                            <?php
                                $postTitle = esc($post['title'] ?? '');
                                $postExcerpt = esc($post['excerpt'] ?? '');
                                $postUrl = '/blog/' . esc($post['category_slug'] ?? $category['slug']) . '/' . esc($post['slug'] ?? '');
                                $dateValue = $post['published_at'] ?? $post['created_at'] ?? null;
                                $postDate = $dateValue ? date('F j, Y', strtotime($dateValue)) : null;
                                $coverImage = $post['cover_image_url'] ?? null;
                            ?>
                            <article class="post-card">
                                <div class="post-image" <?= $coverImage ? 'style="background-size: cover; background-image: url(' . esc($coverImage) . ');"' : ''; ?>>
                                    <span class="post-category-badge"><?= $categoryName; ?></span>
                                </div>
                                <div class="post-body">
                                    <div class="post-meta">
                                        <?php if ($postDate) : ?>
                                            <span class="post-date"><?= esc($postDate); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="post-title">
                                        <a href="<?= $postUrl; ?>"><?= $postTitle; ?></a>
                                    </h3>
                                    <p class="post-excerpt"><?= $postExcerpt; ?></p>
                                    <a href="<?= $postUrl; ?>" class="post-link">Read more →</a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="empty-state">No posts found in this category yet.</p>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if (($totalPages ?? 1) > 1) : ?>
                    <div class="pagination">
                        <?php if (($currentPage ?? 1) > 1) : ?>
                            <a href="/blog/<?= esc($category['slug']); ?>?page=<?= (int) ($currentPage - 1); ?>" class="pagination-item pagination-prev">← Previous</a>
                        <?php endif; ?>

                        <?php for ($page = 1; $page <= ($totalPages ?? 1); $page++) : ?>
                            <?php if ($page === ($currentPage ?? 1)) : ?>
                                <span class="pagination-item pagination-item-active"><?= $page; ?></span>
                            <?php else : ?>
                                <a href="/blog/<?= esc($category['slug']); ?>?page=<?= $page; ?>" class="pagination-item"><?= $page; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if (($currentPage ?? 1) < ($totalPages ?? 1)) : ?>
                            <a href="/blog/<?= esc($category['slug']); ?>?page=<?= (int) ($currentPage + 1); ?>" class="pagination-item pagination-next">Next →</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
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
        navToggle?.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    </script>
</body>
</html>
