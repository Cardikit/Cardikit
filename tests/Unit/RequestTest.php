<?php

use App\Core\Request;

test('request parses json body when content-type is application/json', function () {
    $payload = ['foo' => 'bar', 'baz' => 123];
    $GLOBALS['__test_body'] = $payload;
    $_SERVER['CONTENT_TYPE'] = 'application/json';
    $request = new Request();

    expect($request->body())->toBe($payload);

    unset($GLOBALS['__test_body'], $_SERVER['CONTENT_TYPE']);
});

test('request parses form data as fallback', function () {
    $payload = ['foo' => 'bar'];
    $GLOBALS['__test_body'] = null;

    // simulate form-encoded body
    $content = http_build_query($payload);
    $stream = fopen('php://temp', 'r+');
    fwrite($stream, $content);
    rewind($stream);
    // override php://input read
    stream_wrapper_unregister('php');
    stream_wrapper_register('php', class_exists('PhpInputStream') ? 'PhpInputStream' : 'php_user_filter');

    $_SERVER['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
    $request = new Request();

    expect($request->body())->toBe($payload);

    // cleanup globals/wrappers
    stream_wrapper_restore('php');
    unset($_SERVER['CONTENT_TYPE'], $GLOBALS['__test_body']);
});
