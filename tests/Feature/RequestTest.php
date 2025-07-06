<?php

use App\Core\Request;
use Tests\PhpInputStream;

// Checks that url and method are parsed correctly
test('Request parses GET method and URI correctly', function () {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/example?foo=bar';
    $_GET = ['foo' => 'bar'];

    $request = new Request();

    expect($request->method())->toBe('GET');
    expect($request->uri())->toBe('/example');
    expect($request->query())->toBe(['foo' => 'bar']);
});

// Checks that JSON body is parsed correctly
test('Request parses JSON body correctly', function () {
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['REQUEST_URI'] = '/submit';
    $_GET = [];
    $_SERVER['HTTP_CONTENT_TYPE'] = 'application/json';

    PhpInputStream::$mock = '{"name": "John", "age": 30}';

    stream_wrapper_unregister('php');
    stream_wrapper_register('php', PhpInputStream::class);

    $request = new Request();

    stream_wrapper_restore('php');

    expect($request->body())->toBe(['name' => 'John', 'age' => 30]);
});
