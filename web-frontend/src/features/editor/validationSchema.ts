import * as yup from 'yup';

/**
 * Card & Card Item Validation Schemas
 * -----------------------------------
 * Centralized Yup schemas used by the editor when creating or updating cards.
 * These schemas enforce data correctness before the API request is sent and
 * produce human-readable, context-aware error messages for each field.
 *
 * ⚙️ `itemLabel(type)`
 * - Maps internal card item `type` → human-friendly label prefix.
 * - Used to dynamically generate validation error messages such as:
 *   - “Email item empty”
 *   - “Phone item must be at least 2 characters”
 * - Unknown types fall back to the generic label: `"Card item"`.

 * 🧩 `cardItemSchema`
 * Validates a single card item inside `card_items[]`.
 *
 * Fields:
 * - `id` (optional): Existing card item ID if persisted.
 * - `card_id` (optional): Parent card ID for updates.
 * - `type` (required): Must be defined; determines error label context.
 * - `label`:
 *     - Optional.
 *     - Max length: 255 chars.
 * - `value`:
 *     - Required (after trimming).
 *     - Error messages depend on the item type.
 *     - Minimum length: 2 characters.
 *     - Maximum length: 255 characters.
 * - `position`:
 *     - Optional numeric sort index.
 * - `meta`:
 *     - Arbitrary JSON metadata (optional).
 *
 * Validation behavior:
 * - `.transform()` is used to normalize whitespace.
 * - Error messages reference the item’s type for clearer UX.
 *   Example: “LinkedIn item empty”, “Website item must be less than 255 characters”.
 *
 * 🗂️ `cardSchema`
 * Validates the entire card object submitted to the API.
 *
 * Fields:
 * - `name`:
 *     - Required.
 *     - Trimmed.
 *     - 2–50 characters.
 * - `color`:
 *     - Must be a 3- or 6-digit hex string (`#RRGGBB` or `#RGB`).
 *     - Defaults to Cardikit primary: `#1D4ED8`.
 * - `theme`:
 *     - Optional.
 *     - Max length 50 (prevents invalid slugs).
 *     - Defaults to `"default"` if not set.
 * - `banner_image`:
 *     - Optional string (typically an S3 or base64 ref).
 * - `avatar_image`:
 *     - Optional string.
 * - `card_items`:
 *     - Array of cardItemSchema entries.
 *     - Defaults to empty array for new cards.
 *
 * Usage Notes:
 * - The editor uses `.validate(payload, { abortEarly: false })` so all errors
 *   return at once and can be mapped to per-field or per-item states.
 * - This schema is intentionally strict to prevent malformed submissions
 *   and simplify backend-side validation.
 *
 * @module validationSchema
 * @since 0.0.2
 */
const itemLabel = (type?: string) => {
    const labels: Record<string, string> = {
        name: 'Name item',
        job_title: 'Job title item',
        department: 'Department item',
        company: 'Company item',
        headline: 'Headline item',
        phone: 'Phone item',
        email: 'Email item',
        link: 'Link item',
        address: 'Address item',
        website: 'Website item',
        linkedin: 'LinkedIn item',
        instagram: 'Instagram item',
        calendly: 'Calendly item',
        x: 'X item',
        facebook: 'Facebook item',
        threads: 'Threads item',
        snapchat: 'Snapchat item',
        tiktok: 'TikTok item',
        youtube: 'YouTube item',
        github: 'GitHub item',
        yelp: 'Yelp item',
        venmo: 'Venmo item',
        paypal: 'PayPal item',
        cashapp: 'Cash App item',
        discord: 'Discord item',
        signal: 'Signal item',
        skype: 'Skype item',
        telegram: 'Telegram item',
        twitch: 'Twitch item',
        whatsapp: 'WhatsApp item',
        pronouns: 'Pronouns item',
        bio: 'Bio item',
        portfolio: 'Portfolio item',
    };

    return labels[type ?? ''] ?? 'Card item';
};

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
            if ((value ?? '').length > 255) {
                return this.createError({ message: `${label} must be less than 255 characters` });
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
    color: yup.string()
        .transform((v) => (v ?? '').trim())
        .matches(/^#(?:[A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/, 'Color must be a valid hex code')
        .default('#1D4ED8'),
    theme: yup.string()
        .transform((v) => (v ?? '').trim())
        .max(50, 'Theme is not supported')
        .default('default')
        .optional(),
    banner_image: yup.string().nullable().optional(),
    avatar_image: yup.string().nullable().optional(),
    card_items: yup.array().of(cardItemSchema).default([]),
});
