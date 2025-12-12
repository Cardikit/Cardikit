<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\ContactService;

/**
* Handles incoming contact submissions from public card viewers.
*
* @package App\Controllers
*
* @since 0.0.5
*/
class ContactController
{
    public function store(Request $request): void
    {
        $result = (new ContactService())->store($request->body(), $request);
        Response::json($result['body'], $result['status']);
    }
}
