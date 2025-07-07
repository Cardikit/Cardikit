<?php

namespace App\Core;

use App\Core\Request;

interface Middleware
{
    public function handle(Request $request, callable $next): mixed;
}
