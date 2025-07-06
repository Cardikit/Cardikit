<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\Response;

Router::get('/ping', function () {
    Response::json(['message' => 'pong']);
});

Router::dispatch();
