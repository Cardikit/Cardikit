export interface ThemeMeta {
    slug: string;
    name: string;
    description?: string | null;
    version?: string | null;
    author?: string | null;
    uri?: string | null;
    plan?: 'free' | 'pro' | 'enterprise';
    is_pro?: boolean;
}
