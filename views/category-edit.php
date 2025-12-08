<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edit a blog category.">
    <meta name="theme-color" content="#fa3c25">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <title>Edit Category - Cardikit</title>
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
                    <a href="/blog/categories/admin">Categories</a>
                    <span class="breadcrumb-sep">→</span>
                    <span>Edit</span>
                </div>
                <h1 class="form-hero-title">Edit <span class="highlight">Category</span></h1>
                <p class="form-hero-subtitle">Update details and slug.</p>
            </div>
        </section>

        <section class="form-section">
            <div class="container container-narrow">
                <form class="create-form" id="editCategoryForm">
                    <div class="form-group">
                        <label for="name" class="form-label">Name *</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-input"
                            value="<?= esc($category['name'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="slug" class="form-label">Slug *</label>
                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            class="form-input"
                            value="<?= esc($category['slug'] ?? ''); ?>"
                            required
                        >
                        <span class="form-hint">Lowercase, hyphenated; will be used in URLs.</span>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-textarea form-textarea-sm"
                            rows="3"
                            placeholder="Optional description"
                        ><?= esc($category['description'] ?? ''); ?></textarea>
                    </div>

                    <div id="formStatus" class="form-hint"></div>

                    <div class="form-actions">
                        <a href="/blog/categories/admin" class="btn btn-secondary">Back to categories</a>
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

        const form = document.getElementById('editCategoryForm');
        const statusEl = document.getElementById('formStatus');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            statusEl.textContent = 'Saving...';
            statusEl.style.color = '#555';

            const payload = {
                name: form.name.value.trim(),
                slug: form.slug.value.trim(),
                description: form.description.value
            };

            try {
                const response = await fetch('/blog/categories/<?= (int) ($category['id'] ?? 0); ?>', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const body = await response.json().catch(() => ({}));

                if (response.ok) {
                    statusEl.textContent = 'Saved!';
                    statusEl.style.color = 'green';
                } else {
                    statusEl.textContent = body?.message || (body?.errors ? JSON.stringify(body.errors) : 'Failed to save changes.');
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
