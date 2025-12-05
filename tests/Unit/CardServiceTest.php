<?php

use App\Core\Config;
use App\Services\CardService;

beforeEach(function () {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    // Reset globals the service uses
    $_SESSION = [];
});

test('card service rejects unsupported theme', function () {
    $service = new CardService();
    $result = $service->create([
        'name' => 'My Card',
        'color' => '#fff',
        'theme' => 'nonexistent',
    ], 1);

    expect($result['status'])->toBe(422);
    expect($result['body']['errors']['theme'][0] ?? '')->toContain('Unsupported theme');
});

test('card service rejects unauthorized access on update/delete/qr', function () {
    $service = new CardService();

    $update = $service->update([], 999, 0);
    $delete = $service->delete(999, 0);
    $qr = $service->regenerateQr(999, 0, null);

    expect($update['status'])->toBe(404);
    expect($delete['status'])->toBe(404);
    expect($qr['status'])->toBe(404);
});
