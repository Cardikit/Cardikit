<?php

namespace App\Controllers;

use App\Models\Card;
use App\Core\Response;
use App\Core\Request;
use App\Core\Validator;
use App\Core\Database;
use App\Services\CardItemService;
use App\Services\QrCodeService;

class CardController
{
    public function index(): void
    {
        $user_id = $_SESSION['user_id'];

        $cards = Card::userCardsWithItems($user_id);

        Response::json($cards ?? []);
    }

    public function show(Request $request, int $id): void
    {
        $card = (new Card())->findWithItems($id);

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

        $cardItemsPayload = $data['card_items'] ?? [];
        unset($data['card_items']);

        $data['user_id'] = $_SESSION['user_id'];

        // create card
        $card = (new Card())->create($data);

        if (!$card) {
            Response::json([
                'message' => 'Card creation failed'
            ], 500);
            return;
        }

        // create card items
        [$createdItems, $itemErrors] = (new CardItemService($card['id']))->createCardItems($cardItemsPayload);

        if (!empty($itemErrors)) {
            Response::json([
                'message' => 'Card Creation Failed',
                'card' => $card,
                'items' => $createdItems,
                'errors' => $itemErrors,
            ], 422);

            // delete card if card items failed
            (new Card())->deleteById($card['id']);

            return;
        }

        // generate and persist QR after successful create
        try {
            $qr = (new QrCodeService())->generateForCard($card['id']);
            (new Card())->updateById($card['id'], [
                'qr_url' => $qr['card_url'],
                'qr_image' => $qr['image_url'],
            ]);
        } catch (\Throwable $e) {
            // Fail softly: card is created, but QR failed
        }

        // return success message
        Response::json([
            'message' => 'Card created successfully',
            'card' => Card::findWithItems($card['id']),
            'items' => $createdItems
        ], 201);
    }

    public function update(Request $request, int $id): void
    {
        $data = $request->body();

        // Ensure card exists
        $cardModel = new Card();
        $card = $cardModel->findBy('id', $id);

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

        $cardItemsPayload = $data['card_items'] ?? [];
        unset($data['card_items']);

        // keep existing name if not provided
        $data['name'] = $data['name'] ?? $card['name'];

        $validator = new Validator([Card::class => new Card()]);
        $nameRule = 'required|min:2|max:50|type:string';
        if ($data['name'] !== $card['name']) {
            $nameRule .= '|unique:App\Models\Card:name';
        }
        $valid = $validator->validate($data, [
            'name' => $nameRule,
        ]);

        // return error if input is invalid
        if (!$valid) {
            Response::json([
                'errors' => $validator->errors()
            ], 422);
            return;
        }

        $db = Database::connect();

        try {
            $db->beginTransaction();

            // update card
            $cardModel->updateById($id, $data);

            // sync card items
            [, $itemErrors] = (new CardItemService($id))->syncCardItems($cardItemsPayload);

            if (!empty($itemErrors)) {
                $db->rollBack();
                Response::json([
                    'message' => 'Card update failed',
                    'errors' => $itemErrors
                ], 422);
                return;
            }

            $db->commit();
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            Response::json([
                'message' => 'Card update failed'
            ], 500);
            return;
        }

        // return success message
        Response::json([
            'message' => 'Card updated successfully',
            'card' => Card::findWithItems($id)
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

    public function generateQr(Request $request, int $id): void
    {
        $cardModel = new Card();
        $card = $cardModel->findBy('id', $id);

        if (!$card) {
            Response::json([
                'message' => 'Card not found'
            ], 404);
            return;
        }

        if ($card['user_id'] !== $_SESSION['user_id']) {
            Response::json([
                'message' => 'Unauthorized'
            ], 401);
            return;
        }

        $body = $request->body();
        $logoData = $body['logo'] ?? null;
        var_dump($logoData);

        try {
            $qr = (new QrCodeService())->generateForCard($id, $logoData);
        } catch (\InvalidArgumentException $e) {
            Response::json([
                'message' => $e->getMessage()
            ], 422);
            return;
        } catch (\Throwable $e) {
            Response::json([
                'message' => 'QR code generation failed',
                'error' => $e->getMessage()
            ], 500);
            return;
        }

        // persist QR data to the card (store URL/path, not binary)
        $cardModel->updateById($id, [
            'qr_url' => $qr['card_url'],
            'qr_image' => $qr['image_url'],
        ]);

        Response::json([
            'message' => 'QR code generated',
            'card_url' => $qr['card_url'],
            'qr_image_url' => $qr['image_url'],
            'qr_image_path' => $qr['image_path'],
        ], 200);
    }
}
