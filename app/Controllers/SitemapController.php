<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\Blog;
use App\Models\Category;

class SitemapController
{
    /**
    * Render sitemap.xml
    */
    public function index(): void
    {
        $baseUrl = $this->baseUrl();

        $staticUrls = [
            $baseUrl . '/',
            $baseUrl . '/blog',
            $baseUrl . '/blog/categories',
        ];

        $categories = Category::allOrdered() ?? [];
        $posts = (new Blog())->listAllPublished() ?? [];

        $urls = [];

        foreach ($staticUrls as $url) {
            $urls[] = [
                'loc' => $url,
                'changefreq' => 'weekly',
            ];
        }

        foreach ($categories as $category) {
            $urls[] = [
                'loc' => $baseUrl . '/blog/' . ($category['slug'] ?? ''),
                'changefreq' => 'weekly',
            ];
        }

        foreach ($posts as $post) {
            $lastmod = $post['updated_at'] ?? $post['published_at'] ?? $post['created_at'] ?? null;
            $urls[] = [
                'loc' => $baseUrl . '/blog/' . ($post['category_slug'] ?? '') . '/' . ($post['slug'] ?? ''),
                'lastmod' => $lastmod ? date('c', strtotime($lastmod)) : null,
                'changefreq' => 'monthly',
            ];
        }

        $xml = $this->buildXml($urls);
        Response::html($xml, 200, 'application/xml');
    }

    protected function baseUrl(): string
    {
        $host = $_SERVER['HTTP_HOST'] ?? 'cardikit.com';
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? null) === 443;
        $scheme = $https ? 'https' : 'http';

        return $scheme . '://' . $host;
    }

    /**
    * Build sitemap XML string.
    *
    * @param array<int, array{loc:string, lastmod?:string|null, changefreq?:string|null}> $urls
    */
    protected function buildXml(array $urls): string
    {
        $escape = fn(string $value): string => htmlspecialchars($value, ENT_XML1);

        $lines = [];
        $lines[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $lines[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $entry) {
            $loc = $escape($entry['loc']);
            $lines[] = '  <url>';
            $lines[] = "    <loc>{$loc}</loc>";

            if (!empty($entry['lastmod'])) {
                $lines[] = '    <lastmod>' . $escape($entry['lastmod']) . '</lastmod>';
            }

            if (!empty($entry['changefreq'])) {
                $lines[] = '    <changefreq>' . $escape($entry['changefreq']) . '</changefreq>';
            }

            $lines[] = '  </url>';
        }

        $lines[] = '</urlset>';

        return implode("\n", $lines);
    }
}
