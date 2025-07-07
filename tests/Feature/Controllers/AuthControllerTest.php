<?php

use App\Core\Router;
use App\Controllers\AuthController;

test('GET /csrf-token returns a token and stores it in the session', function () {
    $_SESSION = [];

    Router::get('/csrf-token', [AuthController::class, 'csrfToken']);

    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/csrf-token';

    ob_start();
    Router::dispatch();
    $output = ob_get_clean();

    $json = json_decode($output, true);

    expect($json)->toHaveKey('csrf_token');
    expect(strlen($json['csrf_token']))->toBe(64);
    expect($_SESSION['csrf_token'])->toBe($json['csrf_token']);
});
