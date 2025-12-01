<?php

namespace App\Services;

/**
* Discovers themes and their metadata from the /themes directory.
*
* Themes follow a WordPress-style header comment in style.css, e.g.:
* /*
* Theme Name: Dark Glass
* Description: Sleek dark theme.
* Version: 1.0.0
* Author: Cardikit
* *\/
*/
class ThemeCatalog
{
    protected string $themesPath;

    public function __construct(?string $themesPath = null)
    {
        $this->themesPath = $themesPath ?? dirname(__DIR__, 2) . '/themes';
    }

    /**
    * @return array<int,array{slug:string,name:string,description?:string,version?:string,author?:string,uri?:string}>
    */
    public function getThemes(): array
    {
        if (!is_dir($this->themesPath)) {
            return [];
        }

        $themes = [];
        $directories = glob($this->themesPath . '/*', GLOB_ONLYDIR);

        foreach ($directories as $dir) {
            $slug = basename($dir);
            $stylePath = $dir . '/style.css';
            if (!is_file($stylePath)) {
                continue;
            }

            $meta = $this->parseMeta($stylePath);

            $themes[] = [
                'slug' => $slug,
                'name' => $meta['Theme Name'] ?? ucfirst($slug),
                'description' => $meta['Description'] ?? null,
                'version' => $meta['Version'] ?? null,
                'author' => $meta['Author'] ?? null,
                'uri' => $meta['Theme URI'] ?? null,
            ];
        }

        return $themes;
    }

    public function getSlugs(): array
    {
        return array_map(
            fn($theme) => strtolower($theme['slug']),
            $this->getThemes()
        );
    }

    protected function parseMeta(string $stylePath): array
    {
        $contents = file_get_contents($stylePath);
        if ($contents === false) {
            return [];
        }

        // Look for the first comment block.
        if (!preg_match('#/\\*([^*]|\*(?!/))*\\*/#s', $contents, $match)) {
            return [];
        }

        $block = trim($match[0], "/* \n\r\t");
        $lines = preg_split('/\\r?\\n/', $block);
        $meta = [];

        foreach ($lines as $line) {
            if (!str_contains($line, ':')) continue;
            [$key, $value] = array_map('trim', explode(':', $line, 2));
            if ($key !== '' && $value !== '') {
                $meta[$key] = $value;
            }
        }

        return $meta;
    }
}
