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
                    <li><a href="/blog.html" class="nav-link nav-link-active">Blog</a></li>
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
                    <a href="/blog.html">Blog</a>
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
                <form class="create-form" id="createPostForm">
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
                            <option value="networking">Networking</option>
                            <option value="digital-cards">Digital Cards</option>
                            <option value="personal-branding">Personal Branding</option>
                            <option value="tips-tricks">Tips & Tricks</option>
                            <option value="industry-news">Industry News</option>
                            <option value="success-stories">Success Stories</option>
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

                    <!-- Featured Image -->
                    <div class="form-group">
                        <label class="form-label">Featured Image</label>
                        <div class="file-upload">
                            <input type="file" id="featuredImage" name="featuredImage" class="file-input" accept="image/*">
                            <label for="featuredImage" class="file-label">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                                <span>Click to upload or drag and drop</span>
                                <span class="file-hint">PNG, JPG up to 5MB</span>
                            </label>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="form-group">
                        <label for="content" class="form-label">Content *</label>
                        <div class="editor-toolbar">
                            <button type="button" class="toolbar-btn" title="Bold"><strong>B</strong></button>
                            <button type="button" class="toolbar-btn" title="Italic"><em>I</em></button>
                            <button type="button" class="toolbar-btn" title="Heading">H</button>
                            <button type="button" class="toolbar-btn" title="Link">🔗</button>
                            <button type="button" class="toolbar-btn" title="Quote">"</button>
                            <button type="button" class="toolbar-btn" title="List">•</button>
                        </div>
                        <textarea 
                            id="content" 
                            name="content" 
                            class="form-textarea form-textarea-lg" 
                            placeholder="Write your post content here. You can use Markdown formatting..."
                            required
                        ></textarea>
                    </div>

                    <!-- Tags -->
                    <div class="form-group">
                        <label for="tags" class="form-label">Tags</label>
                        <input 
                            type="text" 
                            id="tags" 
                            name="tags" 
                            class="form-input" 
                            placeholder="networking, tips, career (comma separated)"
                        >
                        <span class="form-hint">Add up to 5 tags to help readers find your post.</span>
                    </div>

                    <!-- Author Info -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="authorName" class="form-label">Author Name *</label>
                            <input 
                                type="text" 
                                id="authorName" 
                                name="authorName" 
                                class="form-input" 
                                placeholder="Your name"
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label for="authorRole" class="form-label">Author Role</label>
                            <input 
                                type="text" 
                                id="authorRole" 
                                name="authorRole" 
                                class="form-input" 
                                placeholder="e.g., Marketing Specialist"
                            >
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary">Save as Draft</button>
                        <button type="submit" class="btn btn-primary">Publish Post</button>
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
                    <a href="/blog.html">Blog</a>
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

        // Form submission
        document.getElementById('createPostForm').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Post created successfully! (Demo)');
        });
    </script>
</body>
</html>
