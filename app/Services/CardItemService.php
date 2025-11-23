<?php

namespace App\Services;

use App\Models\CardItem;
use App\Core\Validator;

class CardItemService
{
    protected int $card_id;

    public function __construct(int $card_id)
    {
        $this->card_id = $card_id;
    }

    public function createCardItems(array $items): array
    {
        $created = [];
        $errors = [];

        foreach ($items as $index => $item) {
            switch ($item['type'] ?? null) {
                case 'name':
                    [$itemResult, $itemError] = $this->createName($item);
                    if ($itemError !== null) {
                        $errors[$index] = $itemError;
                    } else {
                        $created[] = $itemResult;
                    }
                    break;

                default:
                    $errors[$index] = ['type' => 'Unsupported card item type'];
                    break;
            }
        }

        return [$created, $errors];
    }

    /**
    * Syncs card items by creating new ones, updating existing ones,
    * and deleting those missing from the provided payload.
    *
    * @param array $items
    *
    * @return array{array,array} [$updatedItems, $errors]
    */
    public function syncCardItems(array $items): array
    {
        $cardItemModel = new CardItem();
        $existingItems = $cardItemModel->findAllBy('card_id', $this->card_id) ?? [];
        $existingById = [];

        foreach ($existingItems as $item) {
            $existingById[$item['id']] = $item;
        }

        $prepared = [];
        $errors = [];

        foreach ($items as $index => $item) {
            $type = $item['type'] ?? null;

            if ($type !== 'name') {
                $errors[$index] = ['type' => 'Unsupported card item type'];
                continue;
            }

            $validationError = $this->validateName($item);
            if ($validationError !== null) {
                $errors[$index] = $validationError;
                continue;
            }

            $itemId = isset($item['id']) ? (int) $item['id'] : null;
            if ($itemId !== null && !isset($existingById[$itemId])) {
                $errors[$index] = [
                    'type' => 'name',
                    'errors' => ['id' => ['Card item not found for this card']]
                ];
                continue;
            }

            $prepared[] = [
                'id' => $itemId,
                'payload' => $this->mapPayload($item, 'name'),
            ];
        }

        if (!empty($errors)) {
            return [[], $errors];
        }

        $persisted = [];
        $keptIds = [];

        foreach ($prepared as $item) {
            $payload = $item['payload'];

            if ($item['id'] !== null) {
                $cardItemModel->updateById($item['id'], $payload);
                $persistedItem = $cardItemModel->findBy('id', $item['id']);
                $keptIds[] = $item['id'];
            } else {
                $persistedItem = $cardItemModel->create($payload);
                if (isset($persistedItem['id'])) {
                    $keptIds[] = $persistedItem['id'];
                }
            }

            if ($persistedItem) {
                $persisted[] = $persistedItem;
            }
        }

        $idsToDelete = array_diff(array_keys($existingById), $keptIds);
        foreach ($idsToDelete as $idToDelete) {
            $cardItemModel->deleteById($idToDelete);
        }

        return [$persisted, []];
    }

    protected function createName(array $data): ?array
    {
        $validationError = $this->validateName($data);

        if ($validationError !== null) {
            return [null, $validationError];
        }

        $data = $this->mapPayload($data, 'name');

        $item = (new CardItem())->create($data);

        return [$item, null];
    }

    protected function validateName(array $data): ?array
    {
        $validator = new Validator([CardItem::class => new CardItem()]);
        $valid = $validator->validate($data, [
            'value' => 'required|min:2|max:50|type:string',
        ]);

        // return error if input is invalid
        if (!$valid) {
            return ['type' => 'name', 'errors' => $validator->errors()];
        }

        return null;
    }

    /**
    * Normalizes payload for storage and enforces defaults.
    */
    protected function mapPayload(array $data, string $type): array
    {
        return [
            'card_id' => $this->card_id,
            'type' => $type,
            'label' => $data['label'] ?? null,
            'value' => $data['value'],
            'position' => isset($data['position']) ? (int) $data['position'] : 0,
            'meta' => $data['meta'] ?? null,
        ];
    }
}
