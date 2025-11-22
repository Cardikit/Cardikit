export interface CardType {
    id: number;
    name: string;
    items: CardItem[];
}

export interface CardItem {
    type: string;
    value: string;
    position: number;
    id: number;
    card_id: number;
    meta: string;
    created_at: string;
    updated_at: string;
}
