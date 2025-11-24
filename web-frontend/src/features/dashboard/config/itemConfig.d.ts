import type { IconType } from 'react-icons';
export interface ItemFieldConfig {
    link: boolean;
    label: boolean;
}
export interface ItemTypeConfig {
    icon: IconType;
    iconClass?: string;
    fields: ItemFieldConfig;
}
export declare const ITEM_CONFIGS: Record<string, ItemTypeConfig>;
export declare const getItemConfig: (type: string) => ItemTypeConfig;
