<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edit a blog post.">
    <meta name="theme-color" content="#fa3c25">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <title>Edit Post - Cardikit Blog</title>
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
        <section class="form-hero">
            <div class="container">
                <div class="breadcrumb">
                    <a href="/blog">Blog</a>
                    <span class="breadcrumb-sep">→</span>
                    <a href="/blog/admin">Admin</a>
                    <span class="breadcrumb-sep">→</span>
                    <span>Edit</span>
                </div>
                <h1 class="form-hero-title">Edit <span class="highlight">Post</span></h1>
                <p class="form-hero-subtitle">Update the content, category, or status.</p>
            </div>
        </section>

        <section class="form-section">
            <div class="container container-narrow">
                <form class="create-form" id="editBlogForm">
                    <div class="form-group">
                        <label for="title" class="form-label">Title *</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            class="form-input"
                            value="<?= esc($blog['title'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="slug" class="form-label">Slug</label>
                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            class="form-input"
                            placeholder="optional-custom-slug"
                            value="<?= esc($blog['slug'] ?? ''); ?>"
                        >
                        <span class="form-hint">Leave blank to keep the current slug.</span>
                    </div>

                    <div class="form-group">
                        <label for="category" class="form-label">Category *</label>
                        <select id="category" name="category" class="form-select" required>
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= (int) ($category['id'] ?? 0); ?>" <?= isset($blog['category_id']) && (int) $blog['category_id'] === (int) ($category['id'] ?? 0) ? 'selected' : ''; ?>>
                                    <?= esc($category['name'] ?? ''); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Status *</label>
                        <select id="status" name="status" class="form-select" required>
                            <?php $statusValue = $blog['status'] ?? 'draft'; ?>
                            <option value="draft" <?= $statusValue === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?= $statusValue === 'published' ? 'selected' : ''; ?>>Published</option>
                        </select>
                        <span class="form-hint">Published posts will appear live immediately.</span>
                    </div>

                    <div class="form-group">
                        <label for="excerpt" class="form-label">Excerpt</label>
                        <textarea
                            id="excerpt"
                            name="excerpt"
                            class="form-textarea form-textarea-sm"
                            rows="3"
                            placeholder="Short summary for previews"
                        ><?= esc($blog['excerpt'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="content" class="form-label">Content *</label>
                        <textarea
                            id="content"
                            name="content"
                            class="form-textarea form-textarea-lg"
                            rows="8"
                            required
                        ><?= esc($blog['content'] ?? ''); ?></textarea>
                    </div>

                    <div id="formStatus" class="form-hint"></div>

                    <div class="form-actions">
                        <a href="/blog/admin" class="btn btn-secondary">Back to admin</a>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
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

        const form = document.getElementById('editBlogForm');
        const statusEl = document.getElementById('formStatus');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            statusEl.textContent = 'Saving...';
            statusEl.style.color = '#555';

            const payload = {
                title: form.title.value.trim(),
                slug: form.slug.value.trim(),
                category_id: form.category.value ? parseInt(form.category.value, 10) : null,
                status: form.status.value,
                excerpt: form.excerpt.value,
                content: form.content.value
            };

            Object.keys(payload).forEach((key) => {
                if (payload[key] === null || payload[key] === '') {
                    delete payload[key];
                }
            });

            try {
                const response = await fetch('/blog/<?= (int) ($blog['id'] ?? 0); ?>', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const body = await response.json().catch(() => ({}));

                if (response.ok) {
                    statusEl.textContent = 'Saved!';
                    statusEl.style.color = 'green';
                } else {
                    statusEl.textContent = body?.message || 'Failed to save changes.';
                    statusEl.style.color = '#b00020';
                }
            } catch (error) {
                statusEl.textContent = 'Network error while saving.';
                statusEl.style.color = '#b00020';
            }
        });
    </script>
</body>
</html>
