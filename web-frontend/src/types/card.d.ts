export interface CardType {
    id: number;
    slug?: string;
    name: string;
    color?: string;
    theme?: string;
    qr_url?: string;
    qr_image?: string;
    banner_image?: string | null;
    avatar_image?: string | null;
    items: ItemType[];
}

export interface ItemType {
    type: string;
    value: string;
    label?: string;
    position: number;
    id?: number;
    card_id?: number;
    meta?: string | null;
    created_at?: string;
    updated_at?: string;
    client_id?: string;
}
