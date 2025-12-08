<?php

namespace App\Controllers;

use App\Core\View;

/**
* Contains methods to handle the landing page.
*
* @package App\Controllers
*
* @since 0.0.2
*/
class LandingController
{
    /**
    * Displays the landing page.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function show(): void
    {
        // get app url
        $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
        $basePath = '/app';

        // set ctas
        $primaryCta = $appUrl ? $appUrl . $basePath . '/app/register' : $basePath . '/app/register';
        $secondaryCta = $appUrl ? $appUrl . $basePath . '/app/login' : $basePath . '/app/login';

        // sanitize ctas
        $primaryCta = htmlspecialchars($primaryCta, ENT_QUOTES, 'UTF-8');
        $secondaryCta = htmlspecialchars($secondaryCta, ENT_QUOTES, 'UTF-8');

        // render view
        View::render('landing', [
            'primaryCta' => $primaryCta,
            'secondaryCta' => $secondaryCta,
        ]);
    }
}
