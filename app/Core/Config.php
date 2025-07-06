<?php

namespace App\Core;

/**
* Handles loading and accessing environment configuration values.
*
* @package App\Core
*
* @since 0.0.1
*/
class Config
{
    /**
    * Loads key-value pairs from a .env-style file into the $_ENV superglobal.
    *
    * Ignores empty lines and lines starting with '#' (comments).
    *
    * @param string $path The file path to the .env file.
    *
    * @return void
    *
    * @since 0.0.1
    */
    public static function load(string $path): void
    {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }

    /**
    * Retrieves a configuration value from the $_ENV superglobal.
    *
    * @param string $key The key of the configuration value to retrieve.
    * @param mixed $default The default value to return if the key is not found.
    *
    * @return mixed The value of the configuration key, or the default value if not found.
    *
    * @since 0.0.1
    */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}
