<?php

namespace App\Services;

use App\Core\Config;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputInterface;
use GdImage;

/**
 * Generates QR codes that point to public card URLs.
 */
class QrCodeService
{
    /**
     * Build and store a PNG QR code for the given card.
     *
     * @param int $cardId
     * @param string|null $logoData Base64 (optionally data URI) image to overlay in the center of the QR.
     * @param string|null $existingImageUrl Existing QR image URL to delete/replace (for cache busting).
     *
     * @return array{card_url: string, image_url: string, image_path: string}
     */
    public function generateForCard(int $cardId, ?string $logoData = null, ?string $existingImageUrl = null): array
    {
        $logoImage = $logoData ? $this->createLogoImage($logoData) : null;

        $options = new QROptions([
            'version' => 5,
            'outputType' => QROutputInterface::GDIMAGE_PNG,
            'eccLevel' => EccLevel::H,
            'scale' => 8,
            'imageTransparent' => false,
            'drawCircularModules' => true,
            'circleRadius' => 0.45,
            'keepAsSquare' => [
                QRMatrix::M_FINDER,
                QRMatrix::M_FINDER_DOT,
            ],
            'outputBase64' => false,
            'returnResource' => true,
            'addLogoSpace' => $logoImage !== null,
            'logoSpaceWidth' => $logoImage ? 13 : null,
            'logoSpaceHeight' => $logoImage ? 13 : null,
        ]);

        $cardUrl = $this->cardUrl($cardId);
        $qrImage = (new QRCode($options))->render($cardUrl);

        if (!($qrImage instanceof GdImage)) {
            throw new \RuntimeException('Unable to generate QR image resource');
        }

        if ($logoImage instanceof GdImage) {
            $this->overlayLogo($qrImage, $logoImage);
        }

        [$imagePath, $imageUrl] = $this->saveImage($qrImage, $cardId, $existingImageUrl);

        return [
            'card_url' => $cardUrl,
            'image_url' => $imageUrl,
            'image_path' => $imagePath,
        ];
    }

    /**
     * Delete an existing QR image given its public URL.
     */
    public function deleteImage(?string $existingImageUrl): void
    {
        if (!$existingImageUrl) return;

        $path = parse_url($existingImageUrl, PHP_URL_PATH);
        if (!$path) return;

        $publicRoot = dirname(__DIR__, 2) . '/public';
        $storageDir = Config::get('QR_STORAGE_PATH', $publicRoot . '/qrcodes');

        $fileName = basename($path);
        if (!$fileName) return;

        $targetPath = rtrim($storageDir, '/\\') . DIRECTORY_SEPARATOR . $fileName;

        // Only delete files that live inside the configured QR storage directory.
        $realStorage = realpath($storageDir) ?: $storageDir;
        $realTarget = realpath($targetPath) ?: $targetPath;

        if (is_string($realStorage) && is_string($realTarget) && str_starts_with($realTarget, rtrim($realStorage, '/\\') . DIRECTORY_SEPARATOR)) {
            if (is_file($realTarget)) {
                @unlink($realTarget);
            }
        }
    }

    protected function cardUrl(int $cardId): string
    {
        $baseUrl = rtrim(Config::get('APP_URL', 'http://localhost:8080'), '/');
        return $baseUrl . '/c/' . $cardId;
    }

    /**
     * Persist the generated QR to disk and return path + public URL.
     */
    protected function saveImage(GdImage $image, int $cardId, ?string $existingImageUrl = null): array
    {
        $publicRoot = dirname(__DIR__, 2) . '/public';
        $storageDir = Config::get('QR_STORAGE_PATH', $publicRoot . '/qrcodes');

        if (!is_dir($storageDir)) {
            if (!mkdir($storageDir, 0755, true) && !is_dir($storageDir)) {
                throw new \RuntimeException('Failed to create QR storage directory');
            }
        }

        if ($existingImageUrl) {
            $existingName = basename(parse_url($existingImageUrl, PHP_URL_PATH));
            if ($existingName) {
                $existingPath = rtrim($storageDir, '/\\') . DIRECTORY_SEPARATOR . $existingName;
                if (is_file($existingPath)) {
                    @unlink($existingPath);
                }
            }
        }

        $fileName = sprintf('card-%d-%s.png', $cardId, uniqid());
        $filePath = rtrim($storageDir, '/\\') . DIRECTORY_SEPARATOR . $fileName;

        $written = imagepng($image, $filePath);
        if (!$written) {
            throw new \RuntimeException('Failed to write QR image to disk');
        }

        $baseUrl = rtrim(Config::get('QR_BASE_URL', Config::get('APP_URL', 'http://localhost:8080')), '/');

        // Build a URL path segment relative to the public root when using default storage.
        if (str_starts_with($storageDir, $publicRoot)) {
            $relative = ltrim(str_replace($publicRoot, '', $filePath), '/\\');
            $publicPath = '/' . str_replace('\\', '/', $relative);
        } else {
            // Custom storage path: expose filename in URL root (caller can override with QR_BASE_URL for CDN).
            $publicPath = '/' . $fileName;
        }

        return [$filePath, $baseUrl . $publicPath];
    }

    protected function createLogoImage(string $logoData): GdImage
    {
        $binary = $this->decodeBase64Payload($logoData);

        if (strlen($binary) > 10 * 1024 * 1024) { //10 MB
            throw new \InvalidArgumentException('Logo image too large');
        }

        $image = imagecreatefromstring($binary);

        if (!$image instanceof GdImage) {
            throw new \InvalidArgumentException('Invalid logo image data');
        }

        return $image;
    }

    protected function decodeBase64Payload(string $payload): string
    {
        if (str_starts_with($payload, 'data:')) {
            $parts = explode(',', $payload, 2);
            $payload = $parts[1] ?? '';
        }

        $binary = base64_decode($payload, true);

        if ($binary === false) {
            throw new \InvalidArgumentException('Logo data must be valid base64');
        }

        return $binary;
    }

    protected function overlayLogo(GdImage $qr, GdImage $logo): void
    {
        $qrWidth = imagesx($qr);
        $qrHeight = imagesy($qr);

        // Logo takes ~20% of the QR width to keep it scannable.
        $targetSize = (int) round(min($qrWidth, $qrHeight) * 0.2);

        $resizedLogo = imagecreatetruecolor($targetSize, $targetSize);
        imagealphablending($resizedLogo, true);
        imagesavealpha($resizedLogo, true);

        $transparent = imagecolorallocatealpha($resizedLogo, 0, 0, 0, 127);
        imagefill($resizedLogo, 0, 0, $transparent);

        imagecopyresampled(
            $resizedLogo,
            $logo,
            0,
            0,
            0,
            0,
            $targetSize,
            $targetSize,
            imagesx($logo),
            imagesy($logo)
        );

        $destX = (int) (($qrWidth - $targetSize) / 2);
        $destY = (int) (($qrHeight - $targetSize) / 2);

        imagecopy($qr, $resizedLogo, $destX, $destY, 0, 0, $targetSize, $targetSize);
        imagedestroy($resizedLogo);
    }
}
