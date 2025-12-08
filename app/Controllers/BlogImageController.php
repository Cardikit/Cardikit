<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;

class BlogImageController
{
    protected string $uploadDir;
    protected string $uploadUrlPrefix = '/uploads/blog';
    protected int $maxFileSize = 5_000_000; // 5MB
    protected array $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    public function __construct()
    {
        $root = dirname(__DIR__, 2);
        $this->uploadDir = $root . '/public/uploads/blog';
    }

    /**
    * List uploaded blog images (admin view).
    */
    public function index(Request $request): void
    {
        $images = $this->getImages();

        View::render('blog-images', [
            'title' => 'Blog Images',
            'images' => $images,
        ]);
    }

    /**
    * Render upload page.
    */
    public function create(Request $request): void
    {
        View::render('blog-images-upload', [
            'title' => 'Upload Blog Image',
        ]);
    }

    /**
    * Upload an image to the uploads directory.
    */
    public function store(Request $request): void
    {
        if (!$this->ensureUploadDir()) {
            Response::json(['message' => 'Upload directory is not writable.'], 500);
            return;
        }

        if (!isset($_FILES['image'])) {
            Response::json(['message' => 'No file uploaded.'], 422);
            return;
        }

        $file = $_FILES['image'];

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            Response::json(['message' => 'Upload failed.'], 500);
            return;
        }

        if (($file['size'] ?? 0) > $this->maxFileSize) {
            Response::json(['message' => 'File too large (max 5MB).'], 422);
            return;
        }

        $tmpName = $file['tmp_name'] ?? null;
        if (!$tmpName || !is_file($tmpName)) {
            Response::json(['message' => 'Invalid upload.'], 422);
            return;
        }

        $mime = mime_content_type($tmpName) ?: '';
        $extension = $this->allowedMimes[$mime] ?? null;
        if (!$extension) {
            Response::json(['message' => 'Unsupported file type.'], 422);
            return;
        }

        $baseName = pathinfo((string) ($file['name'] ?? ''), PATHINFO_FILENAME);
        $safeBase = strtolower(trim(preg_replace('/[^a-zA-Z0-9_-]+/', '-', $baseName) ?? '', '-'));
        if ($safeBase === '') {
            $safeBase = 'image';
        }

        $filename = $safeBase . '-' . time() . '.' . $extension;
        $targetPath = $this->uploadDir . '/' . $filename;

        if (!move_uploaded_file($tmpName, $targetPath)) {
            Response::json(['message' => 'Failed to save file.'], 500);
            return;
        }

        $url = $this->uploadUrlPrefix . '/' . $filename;

        Response::json([
            'message' => 'Image uploaded',
            'filename' => $filename,
            'url' => $url,
        ], 201);
    }

    /**
    * Delete an uploaded image.
    */
    public function delete(Request $request, string $filename): void
    {
        $safeName = basename($filename);
        if ($safeName !== $filename || !preg_match('/^[a-zA-Z0-9._-]+$/', $safeName)) {
            Response::json(['message' => 'Invalid filename'], 400);
            return;
        }

        $filePath = $this->uploadDir . '/' . $safeName;
        $realPath = realpath($filePath);
        $uploadRoot = realpath($this->uploadDir);

        if (!$realPath || !$uploadRoot || str_starts_with($realPath, $uploadRoot) === false) {
            Response::json(['message' => 'File not found'], 404);
            return;
        }

        if (!is_file($realPath)) {
            Response::json(['message' => 'File not found'], 404);
            return;
        }

        if (!unlink($realPath)) {
            Response::json(['message' => 'Failed to delete file'], 500);
            return;
        }

        Response::json(['message' => 'Image deleted'], 200);
    }

    /**
    * Ensure upload directory exists.
    */
    protected function ensureUploadDir(): bool
    {
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0775, true);
        }

        return is_dir($this->uploadDir) && is_writable($this->uploadDir);
    }

    /**
    * Build list of images with metadata.
    *
    * @return array<int, array<string, mixed>>
    */
    protected function getImages(): array
    {
        $this->ensureUploadDir();

        $files = glob($this->uploadDir . '/*');
        if (!$files) {
            return [];
        }

        $images = [];
        foreach ($files as $filePath) {
            if (!is_file($filePath)) {
                continue;
            }

            $filename = basename($filePath);
            $size = filesize($filePath) ?: 0;
            $modified = filemtime($filePath) ?: time();

            $images[] = [
                'filename' => $filename,
                'url' => $this->uploadUrlPrefix . '/' . $filename,
                'size' => $this->formatBytes($size),
                'modified' => date('F j, Y', $modified),
                'modified_ts' => $modified,
            ];
        }

        usort($images, fn ($a, $b) => ($b['modified_ts'] ?? 0) <=> ($a['modified_ts'] ?? 0));

        return $images;
    }

    /**
    * Format bytes into a readable string.
    */
    protected function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }
        if ($bytes < 1_048_576) {
            return round($bytes / 1024, 1) . ' KB';
        }

        return round($bytes / 1_048_576, 1) . ' MB';
    }
}
