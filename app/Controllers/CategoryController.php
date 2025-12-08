<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Category;
use App\Models\Blog;

class CategoryController
{
    /**
    * List categories and their published posts.
    */
    public function index(Request $request): void
    {
        $categories = Category::allOrderedWithPostCounts() ?? [];

        View::render('categories', [
            'title' => 'Categories',
            'categories' => $categories,
        ]);
    }

    /**
    * Admin: list categories for management.
    */
    public function adminIndex(): void
    {
        $categories = Category::allOrderedWithPostCounts() ?? [];

        View::render('categories-admin', [
            'title' => 'Manage Categories',
            'categories' => $categories,
        ]);
    }

    /**
    * Show a category and its published posts.
    */
    public function show(Request $request, string $slug): void
    {
        $category = Category::findBySlug($slug);
        if (!$category) {
            View::render('404', [], 404);
            return;
        }

        $page = (int) ($request->query()['page'] ?? 1);
        $currentPage = $page > 0 ? $page : 1;
        $perPage = 20;

        $blogModel = new Blog();
        $totalPosts = $blogModel->countPublishedByCategory((int) $category['id']);
        $totalPages = max(1, (int) ceil($totalPosts / $perPage));
        $currentPage = min($currentPage, $totalPages);

        $offset = ($currentPage - 1) * $perPage;
        $posts = $blogModel->listPublished((int) $category['id'], $perPage, $offset) ?? [];

        View::render('category', [
            'title' => $category['name'],
            'category' => $category,
            'posts' => $posts,
            'totalPosts' => $totalPosts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }

    /**
    * Admin: edit category form.
    */
    public function edit(Request $request, int $id): void
    {
        $category = Category::findById($id);

        if (!$category) {
            Response::html('Category not found', 404);
            return;
        }

        View::render('category-edit', [
            'title' => 'Edit Category',
            'category' => $category,
        ]);
    }

    /**
    * Render category creation form.
    */
    public function create(): void
    {
        View::render('category-create', [
            'title' => 'Create Category',
        ]);
    }

    /**
    * Create a category (admin).
    */
    public function store(Request $request): void
    {
        $payload = $request->body();

        $name = isset($payload['name']) ? trim((string) $payload['name']) : '';
        $slug = isset($payload['slug']) ? trim((string) $payload['slug']) : '';
        $description = isset($payload['description']) ? trim((string) $payload['description']) : null;
        $image = isset($payload['image']) ? trim((string) $payload['image']) : null;

        if ($name === '') {
            Response::json(['errors' => ['name' => ['Name is required']]], 422);
            return;
        }

        $slug = $slug !== '' ? $slug : $name;
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $slug) ?? '');
        $slug = trim($slug, '-');

        if ($slug === '') {
            Response::json(['errors' => ['slug' => ['Invalid slug']]], 422);
            return;
        }

        if (Category::findBySlug($slug)) {
            Response::json(['errors' => ['slug' => ['Slug already exists']]], 422);
            return;
        }

        $created = (new Category())->create([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'image' => $image ?: null,
        ]);

        if (!$created) {
            Response::json(['message' => 'Failed to create category'], 500);
            return;
        }

        Response::json(['message' => 'Category created', 'category' => $created], 201);
    }

    /**
    * Update a category (admin).
    */
    public function update(Request $request, int $id): void
    {
        $payload = $request->body();
        $category = Category::findById($id);

        if (!$category) {
            Response::json(['message' => 'Category not found'], 404);
            return;
        }

        $name = array_key_exists('name', $payload) ? trim((string) $payload['name']) : $category['name'];
        $slug = array_key_exists('slug', $payload) ? trim((string) $payload['slug']) : $category['slug'];
        $description = array_key_exists('description', $payload) ? trim((string) $payload['description']) : $category['description'];
        $image = array_key_exists('image', $payload) ? trim((string) $payload['image']) : $category['image'] ?? null;

        if ($name === '') {
            Response::json(['errors' => ['name' => ['Name is required']]], 422);
            return;
        }

        $slug = $slug !== '' ? $slug : $name;
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $slug) ?? '');
        $slug = trim($slug, '-');

        if ($slug === '') {
            Response::json(['errors' => ['slug' => ['Invalid slug']]], 422);
            return;
        }

        $existing = Category::findBySlug($slug);
        if ($existing && (int) $existing['id'] !== (int) $id) {
            Response::json(['errors' => ['slug' => ['Slug already exists']]], 422);
            return;
        }

        $updated = (new Category())->updateById($id, [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'image' => $image ?: null,
        ]);

        if (!$updated) {
            Response::json(['message' => 'Failed to update category'], 500);
            return;
        }

        Response::json(['message' => 'Category updated', 'category' => Category::findById($id)], 200);
    }

    /**
    * Delete a category (admin).
    */
    public function delete(Request $request, int $id): void
    {
        $category = Category::findById($id);
        if (!$category) {
            Response::json(['message' => 'Category not found'], 404);
            return;
        }

        $deleted = (new Category())->deleteById($id);
        if (!$deleted) {
            Response::json(['message' => 'Failed to delete category'], 500);
            return;
        }

        Response::json(['message' => 'Category deleted'], 200);
    }
}
