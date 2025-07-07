<?php

use App\Core\Validator;
use App\Core\Database;

/**
* Tests all types of validations.
* Ensures all validations pass and fail as expected.
*
* @since 0.0.1
*/
it('validator fails required fields', function () {
    $validator = new Validator();
    $valid = $validator->validate(['name' => ''], ['name' => 'required']);
    expect($valid)->toBeFalse();
    expect($validator->errors())->not->toBe([]);
});

it('validator passes required fields', function () {
    $validator = new Validator();
    $valid = $validator->validate(['name' => 'John'], ['name' => 'required']);
    expect($valid)->toBeTrue();
    expect($validator->errors())->toBe([]);
});

it('validator fails email', function () {
    $validator = new Validator();
    $valid = $validator->validate(['email' => 'john'], ['email' => 'email']);
    expect($valid)->toBeFalse();
    expect($validator->errors())->not->toBe([]);
});

it('validator passes email', function () {
    $validator = new Validator();
    $valid = $validator->validate(['email' => '0Ej2H@example.com'], ['email' => 'email']);
    expect($valid)->toBeTrue();
    expect($validator->errors())->toBe([]);
});

it('validator fails min length', function () {
    $validator = new Validator();
    $valid = $validator->validate(['name' => 'John'], ['name' => 'min:12']);
    expect($valid)->toBeFalse();
    expect($validator->errors())->not->toBe([]);
});

it('validator passes min length', function () {
    $validator = new Validator();
    $valid = $validator->validate(['name' => 'John'], ['name' => 'min:3']);
    expect($valid)->toBeTrue();
    expect($validator->errors())->toBe([]);
});

it('validator fails max length', function () {
    $validator = new Validator();
    $valid = $validator->validate(['name' => 'John'], ['name' => 'max:2']);
    expect($valid)->toBeFalse();
    expect($validator->errors())->not->toBe([]);
});

it('validator passes max length', function () {
    $validator = new Validator();
    $valid = $validator->validate(['name' => 'John'], ['name' => 'max:5']);
    expect($valid)->toBeTrue();
});

it('validator fails type', function () {
    $validator = new Validator();
    $valid = $validator->validate(['name' => 5], ['name' => 'type:string']);
    expect($valid)->toBeFalse();
    expect($validator->errors())->not->toBe([]);
});

it('validator passes type', function () {
    $validator = new Validator();
    $valid = $validator->validate(['name' => 'John'], ['name' => 'type:string']);
    expect($valid)->toBeTrue();
    expect($validator->errors())->toBe([]);
});

it('validator fails confirmed', function () {
    $validator = new Validator();
    $valid = $validator->validate(['password' => 'password', 'password_confirmation' => 'password1'], ['password' => 'confirmed']);
    expect($valid)->toBeFalse();
    expect($validator->errors())->not->toBe([]);
});

it('validator passes confirmed', function () {
    $validator = new Validator();
    $valid = $validator->validate(['password' => 'password', 'password_confirmation' => 'password'], ['password' => 'confirmed']);
    expect($valid)->toBeTrue();
    expect($validator->errors())->toBe([]);
});

it('passes all rules when valid', function () {
    $validator = new Validator();
    $valid = $validator->validate([
        'name' => 'Valid',
        'email' => 'valid@example.com',
        'password' => 'strongpass',
        'password_confirmation' => 'strongpass'
    ], [
        'name' => 'required|min:2|max:10|type:string',
        'email' => 'required|email',
        'password' => 'required|min:8|type:string|confirmed'
    ]);

    expect($valid)->toBeTrue();
    expect($validator->errors())->toBe([]);
});

it('handles unique validation', function () {
    $mockUser = new class {
        public function findBy(string $column, mixed $value): ?array
        {
            if ($column === 'email' && $value === 'test@example.com') {
                return ['email' => $value];
            }

            return null;
        }
    };

    $validator = new Validator([App\Models\User::class => $mockUser]);

   $data = [
        'email' => 'test@example.com'
   ];

    $valid = $validator->validate($data, [
        'email' => 'required|email|unique:App\Models\User:email'
    ]);

    expect($valid)->toBeFalse();
    expect($validator->errors())->toHaveKey('email');
});
