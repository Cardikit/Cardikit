<?php

namespace App\Controllers;

class LandingController
{
    public function show(): void
    {
        $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
        $basePath = '/app';
        $primaryCta = $appUrl ? $appUrl . $basePath . '/app/register' : $basePath . '/app/register';
        $secondaryCta = $appUrl ? $appUrl . $basePath . '/app/login' : $basePath . '/app/login';

        header('Content-Type: text/html; charset=utf-8');

        $primaryCta = htmlspecialchars($primaryCta, ENT_QUOTES, 'UTF-8');
        $secondaryCta = htmlspecialchars($secondaryCta, ENT_QUOTES, 'UTF-8');

        $view = dirname(__DIR__, 2) . '/views/landing.php';
        if (is_file($view)) {
            include $view;
            return;
        }

        echo 'Landing page missing';
    }
}
