import type { IconType } from 'react-icons';
export type ItemFieldKey = 'label' | 'value';
export interface ItemFieldConfig {
    key: ItemFieldKey;
    label: string;
    placeholder?: string;
}
export interface ItemTypeConfig {
    displayName: string;
    icon: IconType;
    accentClass: string;
    iconClass?: string;
    fields: ItemFieldConfig[];
}
export declare const ITEM_CONFIGS: Record<string, ItemTypeConfig>;
export declare const ITEM_ORDER: readonly ["name", "job_title", "department", "company", "headline", "phone", "email", "link", "address", "website", "linkedin", "instagram", "calendly", "x", "facebook", "threads", "snapchat", "tiktok", "youtube", "github", "yelp", "venmo", "paypal", "cashapp", "discord", "signal", "skype", "telegram", "twitch", "whatsapp", "pronouns", "bio", "portfolio"];
export declare const getItemConfig: (type: string) => ItemTypeConfig;
