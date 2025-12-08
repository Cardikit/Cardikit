<?php

/**
* Bootstraps the application.
* Sets up the environment and starts the session.
*
* @since 0.0.2
*/

use App\Core\Config;

require_once __DIR__ . '/vendor/autoload.php';

Config::load(__DIR__ . '/.env');

$appEnv = strtolower((string) Config::get('APP_ENV', 'production'));
$isProd = $appEnv === 'production';
ini_set('display_errors', $isProd ? '0' : '1');
ini_set('display_startup_errors', $isProd ? '0' : '1');
error_reporting(E_ALL);
ini_set('log_errors', '1');

// Configure and start session globally so all entrypoints share the same policy.
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['SERVER_PORT'] ?? null) == 443)
    || (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => Config::get('SESSION_DOMAIN', ''),
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('cardikit_session');
    session_start();
}
