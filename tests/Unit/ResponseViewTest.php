<?php

use App\Core\Response;

test('response view renders template with data', function () {
    $viewPath = tempnam(sys_get_temp_dir(), 'view_') . '.php';
    file_put_contents($viewPath, '<p>Hello <?= htmlspecialchars($name, ENT_QUOTES, "UTF-8"); ?></p>');

    ob_start();
    Response::view($viewPath, ['name' => 'Tester']);
    $output = trim((string) ob_get_clean());

    expect($output)->toBe('<p>Hello Tester</p>');
    expect(http_response_code())->toBe(200);

    @unlink($viewPath);
});
