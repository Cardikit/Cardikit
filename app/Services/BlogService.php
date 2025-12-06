<?php

namespace App\Services;

use App\Core\Validator;
use App\Models\Blog;
use App\Models\Category;

/**
* Handles blog CRUD and public retrieval.
*
* @package App\Services
*
* @since 0.0.3
*/
class BlogService
{
    /**
    * List published posts, optionally filtered by category slug.
    *
    * @param string|null $categorySlug
    * @param int $limit
    * @param int $offset
    *
    * @return array<int, array>
    */
    public function listPublished(?string $categorySlug = null, int $limit = 20, int $offset = 0): array
    {
        $categoryId = null;
        if ($categorySlug !== null) {
            $category = Category::findBySlug($categorySlug);
            if (!$category) {
                return [];
            }
            $categoryId = (int) $category['id'];
        }

        return (new Blog())->listPublished($categoryId, $limit, $offset) ?? [];
    }

    /**
    * Fetch a single published post by category and slug.
    *
    * @param string $categorySlug
    * @param string $slug
    *
    * @return array|null
    */
    public function getPublished(string $categorySlug, string $slug): ?array
    {
        return (new Blog())->findPublishedByCategoryAndSlug($categorySlug, $slug);
    }

    /**
    * Create a blog post.
    *
    * @param array $payload
    * @param int $userId
    *
    * @return array{status:int, body:array}
    */
    public function create(array $payload, int $userId): array
    {
        $normalized = $this->normalizePayload($payload);
        $validator = new Validator([Blog::class => new Blog()]);

        $errors = $validator->validateOrErrors($normalized, [
            'title' => 'required|min:3|max:255|type:string',
            'content' => 'required|min:1|type:string',
            'category_id' => 'required|type:int',
            'status' => 'type:string',
        ]);

        if ($errors !== null) {
            return [
                'status' => 422,
                'body' => ['errors' => $errors],
            ];
        }

        $category = $normalized['category_id'] ? Category::findById((int) $normalized['category_id']) : null;
        if (!$category) {
            return [
                'status' => 422,
                'body' => ['errors' => ['category_id' => ['Category not found']]],
            ];
        }

        $blogModel = new Blog();
        $slug = $blogModel->generateUniqueSlug($normalized['slug'] ?? $normalized['title'] ?? '');

        $publishedAt = $normalized['status'] === 'published'
            ? ($normalized['published_at'] ?? date('Y-m-d H:i:s'))
            : null;

        $record = $blogModel->create([
            ...$normalized,
            'slug' => $slug,
            'user_id' => $userId,
            'published_at' => $publishedAt,
        ]);

        if (!$record) {
            return [
                'status' => 500,
                'body' => ['message' => 'Failed to create blog'],
            ];
        }

        return [
            'status' => 201,
            'body' => [
                'message' => 'Blog created',
                'blog' => $blogModel->findWithCategoryById((int) $record['id']),
            ],
        ];
    }

    /**
    * Update a blog post.
    *
    * @param array $payload
    * @param int $blogId
    *
    * @return array{status:int, body:array}
    */
    public function update(array $payload, int $blogId): array
    {
        $blogModel = new Blog();
        $existing = $blogModel->findWithCategoryById($blogId);
        if (!$existing) {
            return [
                'status' => 404,
                'body' => ['message' => 'Blog not found'],
            ];
        }

        $normalized = $this->normalizePayload($payload, $existing);

        $errors = (new Validator([Blog::class => $blogModel]))->validateOrErrors($normalized, [
            'title' => 'min:3|max:255|type:string',
            'content' => 'min:1|type:string',
            'category_id' => 'type:int',
            'status' => 'type:string',
        ]);

        if ($errors !== null) {
            return [
                'status' => 422,
                'body' => ['errors' => $errors],
            ];
        }

        if (isset($normalized['category_id'])) {
            $category = $normalized['category_id'] ? Category::findById((int) $normalized['category_id']) : null;
            if (!$category) {
                return [
                    'status' => 422,
                    'body' => ['errors' => ['category_id' => ['Category not found']]],
                ];
            }
        }

        $updatePayload = $normalized;

        if (array_key_exists('slug', $payload) || array_key_exists('title', $payload)) {
            $slugBase = $payload['slug'] ?? $payload['title'] ?? $existing['title'];
            $updatePayload['slug'] = $blogModel->generateUniqueSlug($slugBase, $blogId);
        }

        if (isset($updatePayload['status'])) {
            if ($updatePayload['status'] === 'published' && empty($existing['published_at'])) {
                $updatePayload['published_at'] = date('Y-m-d H:i:s');
            }

            if ($updatePayload['status'] !== 'published') {
                $updatePayload['published_at'] = null;
            }
        }

        $updated = $blogModel->updateById($blogId, $updatePayload);

        if (!$updated) {
            return [
                'status' => 500,
                'body' => ['message' => 'Failed to update blog'],
            ];
        }

        return [
            'status' => 200,
            'body' => [
                'message' => 'Blog updated',
                'blog' => $blogModel->findWithCategoryById($blogId),
            ],
        ];
    }

    /**
    * Delete a blog post.
    *
    * @param int $blogId
    *
    * @return array{status:int, body:array}
    */
    public function delete(int $blogId): array
    {
        $blogModel = new Blog();
        $existing = $blogModel->findBy('id', $blogId);
        if (!$existing) {
            return [
                'status' => 404,
                'body' => ['message' => 'Blog not found'],
            ];
        }

        $deleted = $blogModel->deleteById($blogId);
        if (!$deleted) {
            return [
                'status' => 500,
                'body' => ['message' => 'Failed to delete blog'],
            ];
        }

        return [
            'status' => 200,
            'body' => ['message' => 'Blog deleted'],
        ];
    }

    /**
    * Normalize and trim incoming payload.
    *
    * @param array $payload
    * @param array $existing
    *
    * @return array
    */
    protected function normalizePayload(array $payload, array $existing = []): array
    {
        $title = isset($payload['title']) ? trim((string) $payload['title']) : null;
        $content = isset($payload['content']) ? (string) $payload['content'] : null;
        $excerpt = $payload['excerpt'] ?? null;

        if ($excerpt === null && $content !== null) {
            $excerpt = substr(strip_tags($content), 0, 240);
        }

        $status = isset($payload['status']) ? strtolower((string) $payload['status']) : ($existing['status'] ?? 'draft');
        if (!in_array($status, ['draft', 'published'], true)) {
            $status = 'draft';
        }

        return array_filter([
            'title' => $title,
            'slug' => isset($payload['slug']) ? Blog::slugify((string) $payload['slug']) : null,
            'content' => $content,
            'excerpt' => $excerpt,
            'cover_image_url' => $payload['cover_image_url'] ?? null,
            'category_id' => isset($payload['category_id']) ? (int) $payload['category_id'] : null,
            'status' => $status,
            'published_at' => $payload['published_at'] ?? null,
        ], fn ($value) => $value !== null);
    }
}
