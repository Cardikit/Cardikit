import type { IconType } from 'react-icons';
import {
    FaUser,
    FaBriefcase,
    FaBuilding,
    FaHeading,
    FaPhone,
    FaEnvelope,
    FaLink,
    FaMapMarkerAlt,
    FaGlobe,
    FaLinkedin,
    FaInstagram,
    FaCalendarAlt,
    FaTwitter,
    FaFacebook,
    FaHashtag,
    FaSnapchatGhost,
    FaMusic,
    FaYoutube,
    FaGithub,
    FaYelp,
    FaPaypal,
    FaMoneyBillWave,
    FaDiscord,
    FaSkype,
    FaTelegramPlane,
    FaTwitch,
    FaWhatsapp,
} from 'react-icons/fa';

export interface ItemFieldConfig {
    link: boolean;
    label: boolean
}

export interface ItemTypeConfig {
    icon: IconType;
    iconClass?: string;
    fields: ItemFieldConfig;
}

/**
 * Item Config Definitions
 * -----------------------
 * Central configuration map describing how each card item type should be
 * rendered inside Cardikit cards. This determines:
 *
 *  - Which icon to display
 *  - Whether the item should behave as a link (clickable, <a> tag)
 *  - Whether the item has a label/value structure (e.g., "Phone: 555-1234")
 *
 * The UI layer (Card component) relies on this map to dynamically:
 *  - Pick the correct icon component
 *  - Decide if the item should be wrapped in an <a> tag
 *  - Format primary and secondary text based on `label: true|false`
 *
 * Types:
 * - `ItemFieldConfig`:
 *      link:   If true, the item value is treated as a URL and rendered clickable.
 *      label:  If true, the item contains a label/value pair. If false, only value is shown.
 *
 * - `ItemTypeConfig`:
 *      icon:       React icon component to display.
 *      iconClass:  Optional additional CSS classes for icon styling.
 *      fields:     ItemFieldConfig controlling behavior.
 *
 * ITEM_CONFIGS:
 * - A record keyed by item type string, defining the rendering behavior for all supported
 *   profile fields (social links, contact methods, bio sections, etc.).
 * - Used throughout the dashboard to ensure consistent formatting.
 *
 * getItemConfig(type: string):
 * - Returns the registered config if available.
 * - Falls back to a generic link-style config using `FaLink` when the type is unknown.
 *   This ensures forwards compatibility when new item types are introduced.
 *
 * Example usage:
 * ```ts
 * const config = getItemConfig(item.type);
 * const Icon = config.icon;
 * const isLink = config.fields.link;
 * ```
 *
 * @module itemConfig
 * @since 0.0.2
 */
export const ITEM_CONFIGS: Record<string, ItemTypeConfig> = {
    name: {
        icon: FaUser,
        fields: { link: false, label: false },
    },
    job_title: {
        icon: FaBriefcase,
        fields: { link: false, label: false },
    },
    department: {
        icon: FaBuilding,
        fields: { link: false, label: false },
    },
    company: {
        icon: FaBuilding,
        fields: { link: false, label: false },
    },
    headline: {
        icon: FaHeading,
        fields: { link: false, label: false },
    },
    phone: {
        icon: FaPhone,
        fields: { link: true, label: true },
    },
    email: {
        icon: FaEnvelope,
        fields: { link: true, label: true },
    },
    link: {
        icon: FaLink,
        fields: { link: true, label: true },
    },
    address: {
        icon: FaMapMarkerAlt,
        fields: { link: false, label: true },
    },
    website: {
        icon: FaGlobe,
        fields: { link: true, label: true },
    },
    linkedin: {
        icon: FaLinkedin,
        fields: { link: true, label: true },
    },
    instagram: {
        icon: FaInstagram,
        fields: { link: true, label: true },
    },
    calendly: {
        icon: FaCalendarAlt,
        fields: { link: true, label: true },
    },
    x: {
        icon: FaTwitter,
        fields: { link: true, label: true },
    },
    facebook: {
        icon: FaFacebook,
        fields: { link: true, label: true },
    },
    threads: {
        icon: FaHashtag,
        fields: { link: true, label: true },
    },
    snapchat: {
        icon: FaSnapchatGhost,
        fields: { link: true, label: true },
    },
    tiktok: {
        icon: FaMusic,
        fields: { link: true, label: true },
    },
    youtube: {
        icon: FaYoutube,
        fields: { link: true, label: true },
    },
    github: {
        icon: FaGithub,
        fields: { link: true, label: true },
    },
    yelp: {
        icon: FaYelp,
        fields: { link: true, label: true },
    },
    venmo: {
        icon: FaMoneyBillWave,
        fields: { link: true, label: true },
    },
    paypal: {
        icon: FaPaypal,
        fields: { link: true, label: true },
    },
    cashapp: {
        icon: FaMoneyBillWave,
        fields: { link: true, label: true },
    },
    discord: {
        icon: FaDiscord,
        fields: { link: true, label: true },
    },
    signal: {
        icon: FaHashtag,
        fields: { link: true, label: true },
    },
    skype: {
        icon: FaSkype,
        fields: { link: true, label: true },
    },
    telegram: {
        icon: FaTelegramPlane,
        fields: { link: true, label: true },
    },
    twitch: {
        icon: FaTwitch,
        fields: { link: true, label: true },
    },
    whatsapp: {
        icon: FaWhatsapp,
        fields: { link: true, label: true },
    },
    pronouns: {
        icon: FaHashtag,
        fields: { link: false, label: false },
    },
    bio: {
        icon: FaHeading,
        fields: { link: false, label: false },
    },
    portfolio: {
        icon: FaLink,
        fields: { link: false, label: false },
    },
};

export const getItemConfig = (type: string): ItemTypeConfig => {
    if (ITEM_CONFIGS[type]) return ITEM_CONFIGS[type];
    return {
        icon: FaLink,
        fields: { link: true, label: true},
    };
};
