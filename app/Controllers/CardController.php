<?php

namespace App\Controllers;

use App\Core\Response;
use App\Core\Request;
use App\Services\AuthService;
use App\Services\CardService;

/**
* Contains methods to handle card operations.
* This includes basic CRUD operations.
*
* @package App\Controllers
*
* @since 0.0.2
*/
class CardController
{
    /**
    * Lists all cards for the current user.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function index(): void
    {
        // get logged in user ID
        $userId = (new AuthService())->currentUserId();

        // get the user's cards
        $cards = (new CardService())->listForUser($userId ?? 0);

        // return the cards
        Response::json($cards ?? []);
    }

    /**
    * Displays a specific card for the current user.
    *
    * @param Request $request
    * @param int $id The ID of the card to display.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function show(Request $request, int $id): void
    {
        // get logged in user ID
        $userId = (new AuthService())->currentUserId() ?? 0;

        // get the card
        $result = (new CardService())->getOwnedCard($userId, $id);

        // Fail if card not found
        if ($result['status'] !== 200 || empty($result['card'])) {
            $status = $result['status'];
            Response::json(['message' => $status === 401 ? 'Unauthorized' : 'Card not found'], $status);
            return;
        }

        // Return the card
        Response::json($result['card']);
    }

    /**
    * Lists all themes.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function themes(): void
    {
        // get themes
        $themes = (new CardService())->getThemes();

        // return themes
        Response::json($themes);
    }

    /**
    * Creates a new card.
    *
    * @param Request $request Contains the card data.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function create(Request $request): void
    {
        // get card data
        $data = $request->body();

        // get logged in user ID
        $userId = (new AuthService())->currentUserId() ?? 0;

        // create card
        $result = (new CardService())->create($data, $userId);

        // return card
        Response::json($result['body'], $result['status']);
    }

    /**
    * Updates a card.
    *
    * @param Request $request Contains the card data.
    * @param int $id The ID of the card to update.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function update(Request $request, int $id): void
    {
        // get logged in user ID
        $userId = (new AuthService())->currentUserId() ?? 0;

        // update card
        $result = (new CardService())->update($request->body(), $id, $userId);

        // return card
        Response::json($result['body'], $result['status']);
    }

    /**
    * Deletes a card.
    *
    * @param Request $request
    * @param int $id The ID of the card to delete.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function delete(Request $request, int $id): void
    {
        // get logged in user ID
        $userId = (new AuthService())->currentUserId() ?? 0;

        // delete card
        $result = (new CardService())->delete($id, $userId);

        // return card
        Response::json($result['body'], $result['status']);
    }

    /**
    * Generates a QR code for a card.
    *
    * @param Request $request Contains optional logo data.
    * @param int $id The ID of the card to generate the QR code for.
    *
    * @return void
    *
    * @since 0.0.2
    */
    public function generateQr(Request $request, int $id): void
    {
        // get card data
        $body = $request->body();

        // get logged in user ID
        $userId = (new AuthService())->currentUserId() ?? 0;

        // get logo if provided
        $logoData = $body['logo'] ?? null;

        // generate qr
        $result = (new CardService())->regenerateQr($id, $userId, $logoData);

        // return qr
        Response::json($result['body'], $result['status']);
    }
}
