import * as yup from 'yup';
export declare const cardItemSchema: yup.ObjectSchema<{
    id: number | undefined;
    card_id: number | undefined;
    type: string;
    label: string | null | undefined;
    value: string | undefined;
    position: number | undefined;
    meta: {} | null | undefined;
}, yup.AnyObject, {
    id: undefined;
    card_id: undefined;
    type: undefined;
    label: undefined;
    value: undefined;
    position: undefined;
    meta: undefined;
}, "">;
export declare const cardSchema: yup.ObjectSchema<{
    name: string;
    color: string;
    banner_image: string | null | undefined;
    avatar_image: string | null | undefined;
    card_items: {
        value?: string | undefined;
        id?: number | undefined;
        label?: string | null | undefined;
        meta?: {} | null | undefined;
        card_id?: number | undefined;
        position?: number | undefined;
        type: string;
    }[];
}, yup.AnyObject, {
    name: undefined;
    color: "#1D4ED8";
    banner_image: undefined;
    avatar_image: undefined;
    card_items: never[];
}, "">;
