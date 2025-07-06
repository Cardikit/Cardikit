<?php

use App\Core\Response;

// Checks that response outputs JSON with correct status header
test('Response outpus JSON with correct status', function () {
    ob_start();
    Response::json(['success' => true], 201);
    $output = ob_get_clean();

    expect(http_response_code())->toBe(201);
    expect($output)->toBe(json_encode(['success' => true]));
});
