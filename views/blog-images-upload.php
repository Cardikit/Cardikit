<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Upload images for Cardikit blog posts.">
    <meta name="theme-color" content="#fa3c25">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <title>Upload Blog Image - Cardikit</title>
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
                    <a href="/blog/images">Images</a>
                    <span class="breadcrumb-sep">→</span>
                    <span>Upload</span>
                </div>
                <h1 class="form-hero-title">Upload <span class="highlight">Image</span></h1>
                <p class="form-hero-subtitle">Add assets to reuse in your posts.</p>
            </div>
        </section>

        <section class="form-section">
            <div class="container container-narrow">
                <form class="create-form" id="uploadImageForm" enctype="multipart/form-data" novalidate>
                    <div class="form-group">
                        <label for="image" class="form-label">Select image *</label>
                        <input type="file" id="image" name="image" class="form-input" accept="image/*" required>
                        <span class="form-hint">JPG, PNG, WebP, or GIF. Max 5MB.</span>
                    </div>

                    <div id="formErrors" class="form-hint" style="color: #b00020;"></div>
                    <div id="formStatus" class="form-hint"></div>

                    <div class="form-actions">
                        <a href="/blog/images" class="btn btn-secondary">Back to images</a>
                        <button type="submit" class="btn btn-primary">Upload image</button>
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

        const form = document.getElementById('uploadImageForm');
        const errorsEl = document.getElementById('formErrors');
        const statusEl = document.getElementById('formStatus');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            errorsEl.textContent = '';
            statusEl.textContent = 'Uploading...';
            statusEl.style.color = '#555';

            const fileInput = document.getElementById('image');
            if (!fileInput.files || fileInput.files.length === 0) {
                errorsEl.textContent = 'Please choose an image file.';
                statusEl.textContent = '';
                return;
            }

            const formData = new FormData();
            formData.append('image', fileInput.files[0]);

            try {
                const response = await fetch('/blog/images', {
                    method: 'POST',
                    body: formData
                });

                const raw = await response.text();
                let body = {};
                try {
                    body = raw ? JSON.parse(raw) : {};
                } catch (_) {
                    body = { message: raw };
                }

                if (response.ok && body?.url) {
                    window.location.href = '/blog/images';
                    return;
                }

                const message = body?.message || 'Failed to upload image.';
                errorsEl.textContent = message;
                statusEl.textContent = '';
                console.error(`Image upload failed (${response.status}) - ${message}`);
            } catch (error) {
                errorsEl.textContent = 'Network error while uploading.';
                statusEl.textContent = '';
                console.error('Image upload network error', error?.message || error);
            }
        });
    </script>
</body>
</html>
