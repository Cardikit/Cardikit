<?php

use App\Core\Router;
use App\Controllers\PingController;
use App\Core\Config;

require __DIR__ . '/../vendor/autoload.php';

Config::load(__DIR__ . '/../.env');
session_start();

Router::get('/ping/:id', [PingController::class, 'show']);
Router::get('/db', [PingController::class, 'db']);

Router::post('/users', [PingController::class, 'create']);

Router::dispatch();
