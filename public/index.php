<?php

use App\Core\Router;
use App\Controllers\PingController;

require __DIR__ . '/../bootstrap.php';

session_start();

Router::get('/ping/:id', [PingController::class, 'show']);
Router::get('/db', [PingController::class, 'db']);

Router::post('/users', [PingController::class, 'create']);

Router::dispatch();
