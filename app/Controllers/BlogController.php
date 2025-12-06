<?php

namespace App\Controllers;

use App\Core\View;

class BlogController
{
    public function index()
    {
        View::render('blog', ['title' => 'Blog']);
    }
}
