<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create a new blog post on Cardikit.">
    <meta name="theme-color" content="#fa3c25">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <title>Create Post - Cardikit Blog</title>
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
        <!-- Page Header -->
        <section class="form-hero">
            <div class="container">
                <div class="breadcrumb">
                    <a href="/blog">Blog</a>
                    <span class="breadcrumb-sep">→</span>
                    <span>Create Post</span>
                </div>
                <h1 class="form-hero-title">Create New <span class="highlight">Post</span></h1>
                <p class="form-hero-subtitle">Share your knowledge with the Cardikit community.</p>
            </div>
        </section>

        <!-- Create Post Form -->
        <section class="form-section">
            <div class="container container-narrow">
                <form class="create-form" id="createPostForm" novalidate>
                    <!-- Title -->
                    <div class="form-group">
                        <label for="title" class="form-label">Post Title *</label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            class="form-input" 
                            placeholder="Enter a compelling title..."
                            required
                        >
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label for="category" class="form-label">Category *</label>
                        <select id="category" name="category" class="form-select" required>
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= (int) ($category['id'] ?? 0); ?>"><?= esc($category['name'] ?? ''); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Excerpt -->
                    <div class="form-group">
                        <label for="excerpt" class="form-label">Excerpt *</label>
                        <textarea 
                            id="excerpt" 
                            name="excerpt" 
                            class="form-textarea form-textarea-sm" 
                            placeholder="Write a brief summary of your post (max 160 characters)..."
                            maxlength="160"
                            required
                        ></textarea>
                        <span class="form-hint">This will appear in search results and post previews.</span>
                    </div>

                    <!-- Content -->
                    <div class="form-group">
                        <label for="content" class="form-label">Content *</label>
                        <textarea 
                            id="content" 
                            name="content" 
                            class="form-textarea form-textarea-lg" 
                            placeholder="Write your post content here. You can use Markdown formatting..."
                            required
                        ></textarea>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Status *</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="draft">Save as draft</option>
                            <option value="published">Publish now</option>
                        </select>
                        <span class="form-hint">Choose whether to keep this as a draft or publish immediately.</span>
                    </div>

                    <div class="form-group">
                        <label for="cover_image_url" class="form-label">Header Image URL</label>
                        <input
                            type="text"
                            id="cover_image_url"
                            name="cover_image_url"
                            class="form-input"
                            placeholder="https://example.com/image.jpg"
                        >
                        <span class="form-hint">Upload first at <a href="/blog/images">Blog Images</a>, then paste the URL here.</span>
                    </div>

                    <div id="formErrors" class="form-hint" style="color: #b00020;"></div>
                    <div id="formStatus" class="form-hint"></div>

                    <!-- Submit Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save Post</button>
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

        const form = document.getElementById('createPostForm');
        const errorsEl = document.getElementById('formErrors');
        const statusEl = document.getElementById('formStatus');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorsEl.textContent = '';
            statusEl.textContent = 'Saving...';
            statusEl.style.color = '#555';

            const payload = {
                title: form.title.value.trim(),
                category_id: form.category.value ? parseInt(form.category.value, 10) : null,
                excerpt: form.excerpt.value,
                content: form.content.value,
                status: form.status.value,
                cover_image_url: form.cover_image_url.value.trim()
            };

            Object.keys(payload).forEach((key) => {
                if (payload[key] === null || payload[key] === '') {
                    delete payload[key];
                }
            });

            try {
                const response = await fetch('/blog', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const body = await response.json().catch(() => ({}));

                if (response.ok) {
                    window.location.href = '/blog/admin';
                    return;
                }

                const fieldErrors = body?.errors;
                if (fieldErrors && typeof fieldErrors === 'object') {
                    const messages = Object.entries(fieldErrors).flatMap(([field, errs]) => errs.map((err) => `${field}: ${err}`));
                    errorsEl.textContent = messages.join(' | ');
                } else {
                    errorsEl.textContent = body?.message || 'Failed to create post.';
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
