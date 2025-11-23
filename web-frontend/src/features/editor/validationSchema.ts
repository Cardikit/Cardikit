import * as yup from 'yup';

const itemLabel = (type?: string) => type === 'name' ? 'Name item' : 'Card item';

export const cardItemSchema = yup.object({
    id: yup.number().optional(),
    card_id: yup.number().optional(),
    type: yup.string().required('Card item type is required'),
    label: yup.string().nullable().max(255, 'Card item label must be less than 255 characters'),
    value: yup.string()
        .transform((v) => (v ?? '').trim())
        .test('item-required', function (value) {
            const label = itemLabel((this.parent as any)?.type);
            if (!value) {
                return this.createError({ message: `${label} empty` });
            }
            return true;
        })
        .test('item-min', function (value) {
            if (!value) return true;
            const label = itemLabel((this.parent as any)?.type);
            if ((value ?? '').length < 2) {
                return this.createError({ message: `${label} must be at least 2 characters` });
            }
            return true;
        })
        .test('item-max', function (value) {
            if (!value) return true;
            const label = itemLabel((this.parent as any)?.type);
            if ((value ?? '').length > 50) {
                return this.createError({ message: `${label} must be less than 50 characters` });
            }
            return true;
        }),
    position: yup.number().optional(),
    meta: yup.mixed().nullable().optional(),
});

export const cardSchema = yup.object({
    name: yup.string()
        .transform((v) => (v ?? '').trim())
        .required('Card name is required')
        .min(2, 'Card name must be at least 2 characters')
        .max(50, 'Card name must be less than 50 characters'),
    card_items: yup.array().of(cardItemSchema).default([]),
});
