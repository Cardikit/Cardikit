<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create a new blog category on Cardikit.">
    <meta name="theme-color" content="#fa3c25">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <?php include __DIR__ . '/partials/analytics.php'; ?>
    <meta name="robots" content="noindex,nofollow">
    <title>Create Category - Cardikit Blog</title>
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
        <!-- Page Header -->
        <section class="form-hero">
            <div class="container">
                <div class="breadcrumb">
                    <a href="/blog">Blog</a>
                    <span class="breadcrumb-sep">→</span>
                    <a href="/blog/categories/admin">Categories</a>
                    <span class="breadcrumb-sep">→</span>
                    <span>Create Category</span>
                </div>
                <h1 class="form-hero-title">Create New <span class="highlight">Category</span></h1>
                <p class="form-hero-subtitle">Organize your blog content with categories.</p>
            </div>
        </section>

        <!-- Create Category Form -->
        <section class="form-section">
            <div class="container container-narrow">
                <form class="create-form" id="createCategoryForm" novalidate>
                    <!-- Category Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">Category Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-input" 
                            placeholder="e.g., Digital Marketing"
                            required
                        >
                    </div>

                    <!-- Slug -->
                    <div class="form-group">
                        <label for="slug" class="form-label">URL Slug *</label>
                        <div class="input-prefix-group">
                            <span class="input-prefix">/category/</span>
                            <input 
                                type="text" 
                                id="slug" 
                                name="slug" 
                                class="form-input form-input-with-prefix" 
                                placeholder="digital-marketing"
                                required
                            >
                        </div>
                        <span class="form-hint">This will be used in the URL. Use lowercase letters and hyphens only.</span>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">Description *</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            class="form-textarea form-textarea-sm" 
                            placeholder="Write a brief description of this category..."
                            required
                        ></textarea>
                        <span class="form-hint">This helps readers understand what content they'll find in this category.</span>
                    </div>

                    <div class="form-group">
                        <label for="image" class="form-label">Image URL</label>
                        <input
                            type="text"
                            id="image"
                            name="image"
                            class="form-input"
                            placeholder="https://example.com/image.jpg"
                        >
                        <span class="form-hint">Upload first at <a href="/blog/images">Blog Images</a>, then paste the URL here.</span>
                    </div>

                    <!-- Icon Color -->
                    <div id="formErrors" class="form-hint" style="color: #b00020;"></div>
                    <div id="formStatus" class="form-hint"></div>

                    <!-- Submit Buttons -->
                    <div class="form-actions">
                        <a href="/blog/categories/admin" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Category</button>
                    </div>
                </form>
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

        const form = document.getElementById('createCategoryForm');
        const errorsEl = document.getElementById('formErrors');
        const statusEl = document.getElementById('formStatus');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorsEl.textContent = '';
            statusEl.textContent = 'Saving...';
            statusEl.style.color = '#555';

            const payload = {
                name: form.name.value.trim(),
                slug: form.slug.value.trim(),
                description: form.description.value,
                image: form.image.value.trim()
            };

            Object.keys(payload).forEach((key) => {
                if (payload[key] === '') {
                    delete payload[key];
                }
            });

            try {
                const response = await fetch('/blog/categories', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const body = await response.json().catch(() => ({}));

                if (response.ok) {
                    window.location.href = '/blog/categories/admin';
                    return;
                }

                const fieldErrors = body?.errors;
                if (fieldErrors && typeof fieldErrors === 'object') {
                    const messages = Object.entries(fieldErrors).flatMap(([field, errs]) => errs.map((err) => `${field}: ${err}`));
                    errorsEl.textContent = messages.join(' | ');
                } else {
                    errorsEl.textContent = body?.message || 'Failed to create category.';
                }
                errorsEl.style.color = '#b00020';
                statusEl.textContent = '';
            } catch (error) {
                errorsEl.textContent = 'Network error while saving.';
                errorsEl.style.color = '#b00020';
                statusEl.textContent = '';
            }
        });
    </script>
</body>
</html>
