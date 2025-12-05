<?php

namespace App\Services;

use App\Core\Config;

/**
* Contains methods for storing and retriving card images
*
* @package App\Services
*
* @since 0.0.2
*/
class ImageStorageService
{
    /**
    * Store an image, or keep an existing one.
    *
    * @param string|null $data Base64 or data URI string. If null, returns [$existingUrl, $existingPath].
    * @param int $cardId
    * @param string $type 'banner' or 'avatar'
    * @param string|null $existingUrl
    * @param string|null $existingPath
    *
    * @return array{url: ?string, path: ?string}
    *
    * @since 0.0.2
    */
    public function storeOrKeep(?string $data, int $cardId, string $type, ?string $existingUrl, ?string $existingPath): array
    {
        // No new data provided -> keep as-is
        if ($data === null) {
            return ['url' => $existingUrl, 'path' => $existingPath];
        }

        // Empty string signals delete
        if ($data === '') {
            $this->deleteFile($existingPath);
            return ['url' => null, 'path' => null];
        }

        // If data already looks like a URL/path to the same file, keep
        if ($existingUrl && $data === $existingUrl) {
            return ['url' => $existingUrl, 'path' => $existingPath];
        }

        $binary = $this->decodeBase64Payload($data);
        if (strlen($binary) > 5 * 1024 * 1024) { // 5MB cap
            throw new \InvalidArgumentException('Image too large (max 5MB)');
        }

        $info = @getimagesizefromstring($binary);
        if ($info === false || empty($info['mime'])) {
            throw new \InvalidArgumentException('Invalid image data');
        }

        $extension = match ($info['mime']) {
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/webp' => 'webp',
            default => throw new \InvalidArgumentException('Unsupported image type'),
        };

        $publicRoot = dirname(__DIR__, 2) . '/public';
        $storageDir = Config::get('CARD_IMAGE_PATH', $publicRoot . '/images');

        if (!is_dir($storageDir)) {
            if (!mkdir($storageDir, 0755, true) && !is_dir($storageDir)) {
                throw new \RuntimeException('Failed to create image storage directory');
            }
        }

        if (!is_writable($storageDir)) {
            throw new \RuntimeException(sprintf('Image storage directory is not writable: %s', $storageDir));
        }

        $fileName = sprintf('card-%d-%s-%s.%s', $cardId, $type, uniqid(), $extension);
        $filePath = rtrim($storageDir, '/\\') . DIRECTORY_SEPARATOR . $fileName;

        if (file_put_contents($filePath, $binary) === false) {
            throw new \RuntimeException('Failed to save image');
        }

        $baseUrl = rtrim(Config::get('CARD_IMAGE_BASE_URL', Config::get('APP_URL', 'http://localhost:8080')), '/');
        // Build URL relative to public root if stored under it
        if (str_starts_with($storageDir, $publicRoot)) {
            $relative = ltrim(str_replace($publicRoot, '', $filePath), '/\\');
            $publicPath = '/' . str_replace('\\', '/', $relative);
        } else {
            $publicPath = '/' . $fileName;
        }

        // delete old file after successful save
        $this->deleteFile($existingPath);

        return [
            'url' => $baseUrl . $publicPath,
            'path' => $filePath,
        ];
    }

    /**
    * Delete a file.
    *
    * @param string|null $path
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function deleteFile(?string $path): void
    {
        if ($path && is_file($path)) {
            @unlink($path);
        }
    }

    /**
    * Decode a base64 payload.
    *
    * @param string $payload
    *
    * @return string
    *
    * @since 0.0.2
    */
    protected function decodeBase64Payload(string $payload): string
    {
        if (str_starts_with($payload, 'data:')) {
            $parts = explode(',', $payload, 2);
            $payload = $parts[1] ?? '';
        }

        $binary = base64_decode($payload, true);

        if ($binary === false) {
            throw new \InvalidArgumentException('Image data must be valid base64');
        }

        return $binary;
    }
}
