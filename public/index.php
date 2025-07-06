<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

Router::get('/ping', function () {
    return json_encode(['message' => 'pong']);
});

Router::dispatch();
