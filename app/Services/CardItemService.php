<?php

namespace App\Services;

use App\Models\CardItem;
use App\Core\Validator;
use App\Core\Response;

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
                    $errors[$index] = ['type' => ['Unsupported card item type']];
                    break;
            }
        }

        return [$created, $errors];
    }

    protected function createName(array $data): ?array
    {
        $validator = new Validator([CardItem::class => new CardItem()]);
        $valid = $validator->validate($data, [
            'value' => 'required|min:2|max:50|type:string',
        ]);

        // return error if input is invalid
        if (!$valid) {
            return [null, $validator->errors()];
        }

        $data['card_id'] = $this->card_id;

        $item = (new CardItem())->create($data);

        return [$item, null];
    }
}
