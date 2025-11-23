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

const baseAccent = 'bg-primary-500';

export const ITEM_CONFIGS: Record<string, ItemTypeConfig> = {
    name: {
        displayName: 'Name',
        icon: FaUser,
        accentClass: baseAccent,
        fields: [{ key: 'value', label: 'Full name', placeholder: 'John Doe' }],
    },
    job_title: {
        displayName: 'Job Title',
        icon: FaBriefcase,
        accentClass: 'bg-amber-500',
        fields: [{ key: 'value', label: 'Job title', placeholder: 'Product Manager' }],
    },
    department: {
        displayName: 'Department',
        icon: FaBuilding,
        accentClass: 'bg-emerald-500',
        fields: [{ key: 'value', label: 'Department', placeholder: 'Marketing' }],
    },
    company: {
        displayName: 'Company',
        icon: FaBuilding,
        accentClass: 'bg-sky-500',
        fields: [{ key: 'value', label: 'Company', placeholder: 'Cardikit' }],
    },
    headline: {
        displayName: 'Headline',
        icon: FaHeading,
        accentClass: 'bg-purple-500',
        fields: [{ key: 'value', label: 'Headline', placeholder: 'Crafting digital cards' }],
    },
    phone: {
        displayName: 'Phone',
        icon: FaPhone,
        accentClass: 'bg-green-500',
        fields: [
            { key: 'label', label: 'Label (optional)', placeholder: 'Mobile' },
            { key: 'value', label: 'Phone number', placeholder: '+1 (555) 123-4567' }
        ],
    },
    email: {
        displayName: 'Email',
        icon: FaEnvelope,
        accentClass: 'bg-blue-500',
        fields: [
            { key: 'label', label: 'Label (optional)', placeholder: 'Work' },
            { key: 'value', label: 'Email address', placeholder: 'you@company.com' }
        ],
    },
    link: {
        displayName: 'Link',
        icon: FaLink,
        accentClass: 'bg-cyan-500',
        fields: [
            { key: 'label', label: 'Link text', placeholder: 'View profile' },
            { key: 'value', label: 'URL', placeholder: 'https://example.com' },
        ],
    },
    address: {
        displayName: 'Address',
        icon: FaMapMarkerAlt,
        accentClass: 'bg-orange-500',
        fields: [
            { key: 'label', label: 'Label (optional)', placeholder: 'Office' },
            { key: 'value', label: 'Address', placeholder: '123 Main St, City' }
        ],
    },
    website: {
        displayName: 'Website',
        icon: FaGlobe,
        accentClass: 'bg-indigo-600',
        fields: [
            { key: 'label', label: 'Link text', placeholder: 'Company website' },
            { key: 'value', label: 'URL', placeholder: 'https://cardikit.com' },
        ],
    },
    linkedin: {
        displayName: 'LinkedIn',
        icon: FaLinkedin,
        accentClass: 'bg-blue-700',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Connect with me' },
            { key: 'value', label: 'Profile URL', placeholder: 'https://linkedin.com/in/you' }
        ],
    },
    instagram: {
        displayName: 'Instagram',
        icon: FaInstagram,
        accentClass: 'bg-pink-500',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Follow me' },
            { key: 'value', label: 'Handle or URL', placeholder: '@handle' }
        ],
    },
    calendly: {
        displayName: 'Calendly',
        icon: FaCalendarAlt,
        accentClass: 'bg-slate-600',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Schedule a call' },
            { key: 'value', label: 'Calendly link', placeholder: 'https://calendly.com/you' }
        ],
    },
    x: {
        displayName: 'X (Twitter)',
        icon: FaTwitter,
        accentClass: 'bg-gray-900',
        iconClass: 'text-white',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Follow me' },
            { key: 'value', label: 'Handle or URL', placeholder: '@handle' }
        ],
    },
    facebook: {
        displayName: 'Facebook',
        icon: FaFacebook,
        accentClass: 'bg-blue-600',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Follow me' },
            { key: 'value', label: 'Profile URL', placeholder: 'https://facebook.com/you' }
        ],
    },
    threads: {
        displayName: 'Threads',
        icon: FaHashtag,
        accentClass: 'bg-zinc-700',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Follow me' },
            { key: 'value', label: 'Handle', placeholder: '@handle' }
        ],
    },
    snapchat: {
        displayName: 'Snapchat',
        icon: FaSnapchatGhost,
        accentClass: 'bg-yellow-300',
        iconClass: 'text-black',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Follow me' },
            { key: 'value', label: 'Username', placeholder: 'snapname' }
        ],
    },
    tiktok: {
        displayName: 'TikTok',
        icon: FaMusic,
        accentClass: 'bg-fuchsia-600',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Follow me' },
            { key: 'value', label: 'Handle', placeholder: '@handle' }
        ],
    },
    youtube: {
        displayName: 'YouTube',
        icon: FaYoutube,
        accentClass: 'bg-red-600',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Subscribe' },
            { key: 'value', label: 'Channel URL', placeholder: 'https://youtube.com/@you' }
        ],
    },
    github: {
        displayName: 'GitHub',
        icon: FaGithub,
        accentClass: 'bg-gray-800',
        iconClass: 'text-white',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Portfolio' },
            { key: 'value', label: 'Profile URL', placeholder: 'https://github.com/you' }
        ],
    },
    yelp: {
        displayName: 'Yelp',
        icon: FaYelp,
        accentClass: 'bg-red-500',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Leave a review' },
            { key: 'value', label: 'Business URL', placeholder: 'https://yelp.com/biz/...' }
        ],
    },
    venmo: {
        displayName: 'Venmo',
        icon: FaMoneyBillWave,
        accentClass: 'bg-sky-700',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Pay via Venmo' },
            { key: 'value', label: 'Username', placeholder: '@you' }
        ],
    },
    paypal: {
        displayName: 'PayPal',
        icon: FaPaypal,
        accentClass: 'bg-blue-700',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Pay via PayPal' },
            { key: 'value', label: 'PayPal.me link', placeholder: 'https://paypal.me/you' }
        ],
    },
    cashapp: {
        displayName: 'Cash App',
        icon: FaMoneyBillWave,
        accentClass: 'bg-emerald-700',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Pay via Cash App' },
            { key: 'value', label: '$Cashtag', placeholder: '$you' }
        ],
    },
    discord: {
        displayName: 'Discord',
        icon: FaDiscord,
        accentClass: 'bg-indigo-700',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Join Discord' },
            { key: 'value', label: 'Username or URL', placeholder: 'username' }
        ],
    },
    signal: {
        displayName: 'Signal',
        icon: FaHashtag,
        accentClass: 'bg-cyan-600',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Connect with me' },
            { key: 'value', label: 'Number or handle', placeholder: '+1 (555) 123-4567' }
        ],
    },
    skype: {
        displayName: 'Skype',
        icon: FaSkype,
        accentClass: 'bg-sky-600',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Connect with me' },
            { key: 'value', label: 'Username', placeholder: 'live:username' }
        ],
    },
    telegram: {
        displayName: 'Telegram',
        icon: FaTelegramPlane,
        accentClass: 'bg-cyan-700',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Connect with me' },
            { key: 'value', label: 'Handle', placeholder: '@handle' }
        ],
    },
    twitch: {
        displayName: 'Twitch',
        icon: FaTwitch,
        accentClass: 'bg-purple-700',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Follow me' },
            { key: 'value', label: 'Channel URL', placeholder: 'https://twitch.tv/you' }
        ],
    },
    whatsapp: {
        displayName: 'WhatsApp',
        icon: FaWhatsapp,
        accentClass: 'bg-green-600',
        fields: [
            { key: 'label', label: 'Title (optional)', placeholder: 'Connect with me' },
            { key: 'value', label: 'Number', placeholder: '+1 (555) 123-4567' }
        ],
    },
    pronouns: {
        displayName: 'Pronouns',
        icon: FaHashtag,
        accentClass: 'bg-teal-500',
        fields: [{ key: 'value', label: 'Pronouns', placeholder: 'they/them' }],
    },
    bio: {
        displayName: 'Bio',
        icon: FaHeading,
        accentClass: 'bg-indigo-500',
        fields: [{ key: 'value', label: 'Bio', placeholder: 'Short bio' }],
    },
    portfolio: {
        displayName: 'Portfolio',
        icon: FaLink,
        accentClass: 'bg-sky-700',
        fields: [
            { key: 'label', label: 'Link text', placeholder: 'Design portfolio' },
            { key: 'value', label: 'URL', placeholder: 'https://you.com' },
        ],
    },
};

export const ITEM_ORDER = [
    'name',
    'job_title',
    'department',
    'company',
    'headline',
    'phone',
    'email',
    'link',
    'address',
    'website',
    'linkedin',
    'instagram',
    'calendly',
    'x',
    'facebook',
    'threads',
    'snapchat',
    'tiktok',
    'youtube',
    'github',
    'yelp',
    'venmo',
    'paypal',
    'cashapp',
    'discord',
    'signal',
    'skype',
    'telegram',
    'twitch',
    'whatsapp',
    'pronouns',
    'bio',
    'portfolio',
] as const;

export const getItemConfig = (type: string): ItemTypeConfig => {
    if (ITEM_CONFIGS[type]) return ITEM_CONFIGS[type];
    return {
        displayName: 'Link',
        icon: FaLink,
        accentClass: baseAccent,
        fields: [{ key: 'value', label: 'Value', placeholder: 'Value' }],
    };
};
