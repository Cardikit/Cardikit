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

    public function index(Request $request): void
    {
        $service = new ContactService();
        $result = $service->listForCurrentUser($request->query());
        Response::json($result['body'], $result['status']);
    }

    public function export(Request $request): void
    {
        $service = new ContactService();
        $result = $service->exportForCurrentUser($request->query());

        if (($result['status'] ?? 500) !== 200) {
            Response::json($result['body'] ?? ['message' => 'Unable to export'], $result['status']);
            return;
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="contacts.csv"');
        echo $result['body'] ?? '';
    }
}
