<?php

namespace App\Controllers;

use App\Models\Card;
use App\Core\Response;
use App\Core\Request;
use App\Core\Validator;

class CardController
{
    public function index(): void
    {
        $user_id = $_SESSION['user_id'];

        $cards = Card::userCards($user_id);

        Response::json($cards ?? []);
    }

    public function show(Request $request, int $id): void
    {
        $card = (new Card())->findBy('id', $id);

        if (!$card) {
            Response::json([
                'message' => 'Card not found'
            ], 404);
            return;
        }

        Response::json($card);
    }

    public function create(Request $request): void
    {
        $data = $request->body();

        $validator = new Validator([Card::class => new Card()]);
        $valid = $validator->validate($data, [
            'name' => 'required|min:2|max:50|type:string|unique:App\Models\Card:name',
        ]);

        // return error if input is invalid
        if (!$valid) {
            Response::json([
                'errors' => $validator->errors()
            ], 422);
            return;
        }

        $data['user_id'] = $_SESSION['user_id'];

        // create card
        $card = (new Card())->create($data);

        // create card items

        // return success message
        Response::json([
            'message' => 'Card created successfully'
        ], 201);
    }

    public function update(Request $request, int $id): void
    {
        $data = $request->body();

        $validator = new Validator([Card::class => new Card()]);
        $valid = $validator->validate($data, [
            'name' => 'required|min:2|max:50|type:string|unique:App\Models\Card:name',
        ]);

        // return error if input is invalid
        if (!$valid) {
            Response::json([
                'errors' => $validator->errors()
            ], 422);
            return;
        }

        // Ensure card exists
        $card = (new Card())->findBy('id', $id);

        if (!$card) {
            Response::json([
                'message' => 'Card not found'
            ], 404);
            return;
        }

        // Check card owner
        if ($card['user_id'] !== $_SESSION['user_id']) {
            Response::json([
                'message' => 'Unauthorized'
            ], 401);
            return;
        }

        // update card
        (new Card())->updateById($id, $data);

        // return success message
        Response::json([
            'message' => 'Card updated successfully'
        ], 200);
    }

    public function delete(Request $request, int $id): void
    {
        // Ensure card exists
        $card = (new Card())->findBy('id', $id);

        if (!$card) {
            Response::json([
                'message' => 'Card not found'
            ], 404);
            return;
        }

        // Check card owner
        if ($card['user_id'] !== $_SESSION['user_id']) {
            Response::json([
                'message' => 'Unauthorized'
            ], 401);
            return;
        }

        // delete card
        (new Card())->deleteById($id);

        // return success message
        Response::json([
            'message' => 'Card deleted successfully'
        ], 200);
    }
}
