<?php

use App\Core\Response;
use App\Core\Validator;

test('validateOrErrors returns detailed errors', function () {
    $validator = new Validator();

    $errors = $validator->validateOrErrors([], [
        'name' => 'required|min:2',
        'email' => 'required|email',
    ]);

    expect($errors)->not->toBeNull();
    expect($errors['name'][0] ?? '')->toContain('is required');
    expect($errors['email'][0] ?? '')->toContain('is required');
});

test('validateOrRespond emits json errors and status', function () {
    $validator = new Validator();

    ob_start();
    $validator->validateOrRespond([], ['name' => 'required']);
    $output = (string) ob_get_clean();

    $payload = json_decode($output, true);
    expect($payload)->toBeArray();
    expect($payload['errors']['name'][0] ?? '')->toContain('is required');
    expect(http_response_code())->toBe(422);
});
