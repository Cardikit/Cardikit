<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage uploaded images for Cardikit blog posts.">
    <meta name="theme-color" content="#fa3c25">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <?php include __DIR__ . '/partials/analytics.php'; ?>
    <meta name="robots" content="noindex,nofollow">
    <title>Blog Images - Cardikit</title>
    <link rel="stylesheet" href="<?= asset_url('/blog.css') ?>">
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
                    <span>Images</span>
                </div>
                <div class="blog-hero-title">Blog <span class="highlight">Images</span></div>
                <p class="blog-hero-subtitle">Upload, view, and copy URLs for your posts.</p>
                <div style="margin-top: 1rem;">
                    <a href="/blog/admin" class="btn btn-secondary" style="margin-right: 0.5rem;">Back to posts</a>
                    <a href="/blog/images/upload" class="btn btn-primary">Upload image</a>
                </div>
            </div>
        </section>

        <section class="blog-content">
            <div class="container">
                <div class="section-title">All images</div>
                <?php if (!empty($images)) : ?>
                    <div class="blog-grid">
                        <?php foreach ($images as $image) : ?>
                            <article class="post-card">
                                <div class="post-image" style="display: flex; align-items: center; justify-content: center; background: #f5f5f5;">
                                    <img src="<?= esc($image['url'] ?? ''); ?>" alt="<?= esc($image['filename'] ?? ''); ?>" style="max-width: 100%; max-height: 160px; object-fit: contain;">
                                </div>
                                <div class="post-body">
                                    <div class="post-meta">
                                        <span class="post-date"><?= esc($image['modified'] ?? ''); ?></span>
                                        <span class="post-read-time"><?= esc($image['size'] ?? ''); ?></span>
                                    </div>
                                    <h3 class="post-title" style="word-break: break-all;"><?= esc($image['filename'] ?? ''); ?></h3>
                                    <div class="form-group" style="margin-top: 0.5rem;">
                                        <label class="form-label" for="url-<?= esc($image['filename'] ?? ''); ?>">URL</label>
                                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                                            <input id="url-<?= esc($image['filename'] ?? ''); ?>" class="form-input" type="text" readonly value="<?= esc($image['url'] ?? ''); ?>" style="flex: 1;">
                                            <button class="btn btn-secondary copy-btn" data-url="<?= esc($image['url'] ?? ''); ?>" type="button">Copy</button>
                                        </div>
                                    </div>
                                    <div class="post-actions" style="margin-top: 0.75rem; display: flex; gap: 0.5rem;">
                                        <a href="<?= esc($image['url'] ?? ''); ?>" class="post-link" target="_blank" rel="noopener">Open</a>
                                        <button class="btn btn-secondary delete-btn" data-filename="<?= esc($image['filename'] ?? ''); ?>" type="button" style="background: #f7d7d7; color: #b00020;">Delete</button>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p class="empty-state">No images uploaded yet. Add one to see it here.</p>
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

        const copyButtons = document.querySelectorAll('.copy-btn');
        copyButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                const url = btn.getAttribute('data-url');
                if (!url) return;
                navigator.clipboard.writeText(url).then(() => {
                    btn.textContent = 'Copied';
                    setTimeout(() => { btn.textContent = 'Copy'; }, 1200);
                }).catch(() => {
                    btn.textContent = 'Failed';
                    setTimeout(() => { btn.textContent = 'Copy'; }, 1200);
                });
            });
        });

        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach((btn) => {
            btn.addEventListener('click', async () => {
                const filename = btn.getAttribute('data-filename');
                if (!filename) return;

                const confirmed = window.confirm('Are you sure you want to delete this image?');
                if (!confirmed) return;

                btn.disabled = true;
                btn.textContent = 'Deleting...';

                try {
                    const response = await fetch('/blog/images/' + encodeURIComponent(filename), {
                        method: 'DELETE'
                    });

                    if (response.ok) {
                        window.location.reload();
                        return;
                    }

                    const body = await response.json().catch(() => ({}));
                    alert(body?.message || 'Failed to delete image.');
                } catch (error) {
                    alert('Network error while deleting.');
                } finally {
                    btn.disabled = false;
                    btn.textContent = 'Delete';
                }
            });
        });
    </script>
</body>
</html>
