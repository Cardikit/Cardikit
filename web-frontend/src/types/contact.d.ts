export interface Contact {
    id: number;
    card_id?: number | null;
    card_slug?: string | null;
    card_name?: string | null;
    name?: string | null;
    email?: string | null;
    phone?: string | null;
    source_url?: string | null;
    created_at?: string | null;
}

export interface ContactPage {
    data: Contact[];
    total: number;
    page: number;
    per_page: number;
}
