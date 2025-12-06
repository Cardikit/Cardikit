<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create a new blog category on Cardikit.">
    <meta name="theme-color" content="#fa3c25">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/smaller-logo-no-background.png">
    <title>Create Category - Cardikit Blog</title>
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
                    <a href="/categories.html">Categories</a>
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
                <form class="create-form" id="createCategoryForm">
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

                    <!-- Icon Color -->
                    <div class="form-group">
                        <label class="form-label">Category Color *</label>
                        <div class="color-picker">
                            <label class="color-option">
                                <input type="radio" name="color" value="purple" checked>
                                <span class="color-swatch" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></span>
                                <span class="color-name">Purple</span>
                            </label>
                            <label class="color-option">
                                <input type="radio" name="color" value="red">
                                <span class="color-swatch" style="background: linear-gradient(135deg, #FA3C25 0%, #ff6b5b 100%);"></span>
                                <span class="color-name">Red</span>
                            </label>
                            <label class="color-option">
                                <input type="radio" name="color" value="green">
                                <span class="color-swatch" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);"></span>
                                <span class="color-name">Green</span>
                            </label>
                            <label class="color-option">
                                <input type="radio" name="color" value="pink">
                                <span class="color-swatch" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);"></span>
                                <span class="color-name">Pink</span>
                            </label>
                            <label class="color-option">
                                <input type="radio" name="color" value="blue">
                                <span class="color-swatch" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"></span>
                                <span class="color-name">Blue</span>
                            </label>
                            <label class="color-option">
                                <input type="radio" name="color" value="rose">
                                <span class="color-swatch" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);"></span>
                                <span class="color-name">Rose</span>
                            </label>
                        </div>
                    </div>

                    <!-- Icon Selection -->
                    <div class="form-group">
                        <label class="form-label">Category Icon *</label>
                        <div class="icon-picker">
                            <label class="icon-option">
                                <input type="radio" name="icon" value="users" checked>
                                <span class="icon-swatch">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                </span>
                            </label>
                            <label class="icon-option">
                                <input type="radio" name="icon" value="card">
                                <span class="icon-swatch">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        <path d="M7 15h0M2 9h20"></path>
                                    </svg>
                                </span>
                            </label>
                            <label class="icon-option">
                                <input type="radio" name="icon" value="user">
                                <span class="icon-swatch">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </span>
                            </label>
                            <label class="icon-option">
                                <input type="radio" name="icon" value="help">
                                <span class="icon-swatch">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                    </svg>
                                </span>
                            </label>
                            <label class="icon-option">
                                <input type="radio" name="icon" value="document">
                                <span class="icon-swatch">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                    </svg>
                                </span>
                            </label>
                            <label class="icon-option">
                                <input type="radio" name="icon" value="star">
                                <span class="icon-swatch">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="form-group">
                        <label class="form-label">Preview</label>
                        <div class="category-preview">
                            <div class="category-card category-card-preview">
                                <div class="category-card-icon" id="previewIcon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                </div>
                                <h3 class="category-card-title" id="previewTitle">Category Name</h3>
                                <p class="category-card-desc" id="previewDesc">Category description will appear here.</p>
                                <span class="category-card-count">0 articles</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="form-actions">
                        <a href="/categories.html" class="btn btn-secondary">Cancel</a>
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

        // Live preview updates
        const nameInput = document.getElementById('name');
        const descInput = document.getElementById('description');
        const previewTitle = document.getElementById('previewTitle');
        const previewDesc = document.getElementById('previewDesc');
        const previewIcon = document.getElementById('previewIcon');

        nameInput.addEventListener('input', () => {
            previewTitle.textContent = nameInput.value || 'Category Name';
        });

        descInput.addEventListener('input', () => {
            previewDesc.textContent = descInput.value || 'Category description will appear here.';
        });

        // Color picker
        const colorInputs = document.querySelectorAll('input[name="color"]');
        const colorGradients = {
            purple: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            red: 'linear-gradient(135deg, #FA3C25 0%, #ff6b5b 100%)',
            green: 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
            pink: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            blue: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            rose: 'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)'
        };

        colorInputs.forEach(input => {
            input.addEventListener('change', () => {
                previewIcon.style.background = colorGradients[input.value];
            });
        });

        // Form submission
        document.getElementById('createCategoryForm').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Category created successfully! (Demo)');
        });
    </script>
</body>
</html>
