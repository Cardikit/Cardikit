<?php

namespace App\Services;

use League\CommonMark\CommonMarkConverter;

/**
* Lightweight Markdown renderer using league/commonmark.
*
* @since 0.0.4
*/
class Markdown
{
    protected static ?CommonMarkConverter $converter = null;

    /**
    * Convert markdown to HTML with safe defaults.
    */
    public static function convert(string $markdown): string
    {
        $converter = self::converter();

        $html = $converter->convert($markdown)->getContent();

        // Add lazy loading to images rendered from markdown.
        return preg_replace('/<img(?![^>]*\\bloading=)/i', '<img loading="lazy"', $html) ?? $html;
    }

    /**
    * Get shared converter instance.
    */
    protected static function converter(): CommonMarkConverter
    {
        if (self::$converter === null) {
            self::$converter = new CommonMarkConverter([
                'html_input' => 'escape', // prevent raw HTML injection
                'allow_unsafe_links' => false,
            ]);
        }

        return self::$converter;
    }
}
