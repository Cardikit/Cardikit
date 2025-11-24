export interface CardType {
    id: number;
    name: string;
    qr_url?: string;
    qr_image?: string;
    items: ItemType[];
}

export interface ItemType {
    type: string;
    value: string;
    label?: string;
    position: number;
    id?: number;
    card_id?: number;
    meta?: string;
    created_at?: string;
    updated_at?: string;
    client_id?: string;
}
