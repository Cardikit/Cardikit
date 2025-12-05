<?php

use App\Services\ThemeCatalog;

test('theme catalog parses metadata from style.css', function () {
    $themesDir = sys_get_temp_dir() . '/themes_' . uniqid();
    $slug = 'sample';
    $themePath = $themesDir . '/' . $slug;
    mkdir($themePath, 0777, true);

    $style = <<<CSS
/*
Theme Name: Sample Theme
Description: Nice theme
Version: 1.0.0
Author: Cardikit
*/
body { color: red; }
CSS;
    file_put_contents($themePath . '/style.css', $style);

    $catalog = new ThemeCatalog($themesDir);
    $themes = $catalog->getThemes();

    expect($themes)->toHaveCount(1);
    expect($themes[0]['slug'])->toBe($slug);
    expect($themes[0]['name'])->toBe('Sample Theme');
    expect($themes[0]['description'])->toBe('Nice theme');

    // cleanup
    @unlink($themePath . '/style.css');
    @rmdir($themePath);
    @rmdir($themesDir);
});
