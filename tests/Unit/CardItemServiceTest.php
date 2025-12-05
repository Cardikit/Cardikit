<?php

use App\Services\CardItemService;

test('card item service rejects unsupported type', function () {
    $service = new CardItemService(1);
    [$created, $errors] = $service->createCardItems([
        ['type' => 'unknown', 'value' => 'noop'],
    ]);

    expect($created)->toBeEmpty();
    expect($errors)->not->toBeEmpty();
});
