<?php

namespace App\Services;

/**
* Estimate reading time for blog content.
*
* @since 0.0.4
*/
class ReadingTime
{
    /**
    * Estimate reading time in minutes.
    *
    * @param string $markdownContent
    * @param int $wordsPerMinute
    * @param int $imageCount Optional number of images to add a small penalty for.
    *
    * @return int
    */
    public static function estimate(string $markdownContent, int $wordsPerMinute = 200, int $imageCount = 0): int
    {
        $text = strip_tags(Markdown::convert($markdownContent));
        $words = str_word_count($text);
        $minutes = $wordsPerMinute > 0 ? (int) ceil($words / $wordsPerMinute) : 1;

        // Add ~10 seconds per image => ~0.17 minutes
        if ($imageCount > 0) {
            $minutes += (int) ceil(($imageCount * 10) / 60);
        }

        return max(1, $minutes);
    }
}
