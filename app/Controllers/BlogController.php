<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Response;
use App\Core\Request;
use App\Models\Category;
use App\Models\Blog;
use App\Services\AuthService;
use App\Services\BlogService;

class BlogController
{
    /**
    * List published blog posts.
    */
    public function index(Request $request): void
    {
        $posts = (new Blog())->listPublished(null, 5) ?? [];
        $categories = Category::latest(5) ?? [];

        View::render('blog', [
            'title' => 'Blog',
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    /**
    * Show a single published post.
    */
    public function show(Request $request, string $category, string $slug): void
    {
        $service = new BlogService();
        $post = $service->getPublished($category, $slug);

        if (!$post) {
            Response::html('Post not found', 404);
            return;
        }

        $recentPosts = (new Blog())->listPublished(null, 3) ?? [];
        $categories = Category::latest(5) ?? [];

        View::render('post', [
            'title' => $post['title'],
            'post' => $post,
            'recentPosts' => $recentPosts,
            'categories' => $categories,
        ]);
    }

    public function create(Request $request): void
    {
        $categories = Category::allOrdered() ?? [];

        View::render('blog-create', [
            'title' => 'Create Blog Post',
            'categories' => $categories,
        ]);
    }

    /**
    * Admin: list all blog posts for management.
    */
    public function adminIndex(Request $request): void
    {
        $blogs = (new Blog())->listAllWithCategory(100) ?? [];

        View::render('blog-admin', [
            'title' => 'Manage Blog Posts',
            'blogs' => $blogs,
        ]);
    }

    /**
    * Admin: edit a blog post.
    */
    public function edit(Request $request, int $id): void
    {
        $blog = (new Blog())->findWithCategoryById($id);

        if (!$blog) {
            Response::html('Blog not found', 404);
            return;
        }

        $categories = Category::allOrdered() ?? [];

        View::render('blog-edit', [
            'title' => 'Edit Blog Post',
            'blog' => $blog,
            'categories' => $categories,
        ]);
    }

    /**
    * Create a new blog post (admin only).
    */
    public function store(Request $request): void
    {
        $userId = (new AuthService())->currentUserId() ?? 0;
        $result = (new BlogService())->create($request->body(), $userId);

        Response::json($result['body'], $result['status']);
    }

    /**
    * Update an existing blog post (admin only).
    */
    public function update(Request $request, int $id): void
    {
        $result = (new BlogService())->update($request->body(), $id);

        Response::json($result['body'], $result['status']);
    }

    /**
    * Delete a blog post (admin only).
    */
    public function delete(Request $request, int $id): void
    {
        $result = (new BlogService())->delete($id);

        Response::json($result['body'], $result['status']);
    }
}
