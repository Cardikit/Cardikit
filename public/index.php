<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\PingController;

Router::get('/ping/:id', [PingController::class, 'show']);

Router::post('/users', [PingController::class, 'create']);

Router::dispatch();
