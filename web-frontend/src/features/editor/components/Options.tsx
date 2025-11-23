import {
    Drawer,
    DrawerClose,
    DrawerContent,
} from '@/components/ui/drawer';
import type { CardType } from '@/types/card';
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

interface OptionsProps {
    open: boolean;
    setOpen: (open: boolean) => void;
    card: CardType;
    setCard: (card: CardType) => void;
}

const Options: React.FC<OptionsProps> = ({ open, setOpen, card, setCard }) => {

    const options = [
        { type: 'name', label: 'Name', icon: FaUser },
        { type: 'job_title', label: 'Job Title', icon: FaBriefcase },
        { type: 'department', label: 'Department', icon: FaBuilding },
        { type: 'company', label: 'Company', icon: FaBuilding },
        { type: 'headline', label: 'Headline', icon: FaHeading },
        { type: 'phone', label: 'Phone', icon: FaPhone },
        { type: 'email', label: 'Email', icon: FaEnvelope },
        { type: 'link', label: 'Link', icon: FaLink },
        { type: 'address', label: 'Address', icon: FaMapMarkerAlt },
        { type: 'website', label: 'Website', icon: FaGlobe },
        { type: 'linkedin', label: 'LinkedIn', icon: FaLinkedin },
        { type: 'instagram', label: 'Instagram', icon: FaInstagram },
        { type: 'calendly', label: 'Calendly', icon: FaCalendarAlt },
        { type: 'x', label: 'X (Twitter)', icon: FaTwitter },
        { type: 'facebook', label: 'Facebook', icon: FaFacebook },
        { type: 'threads', label: 'Threads', icon: FaHashtag },
        { type: 'snapchat', label: 'Snapchat', icon: FaSnapchatGhost },
        { type: 'tiktok', label: 'TikTok', icon: FaMusic },
        { type: 'youtube', label: 'YouTube', icon: FaYoutube },
        { type: 'github', label: 'GitHub', icon: FaGithub },
        { type: 'yelp', label: 'Yelp', icon: FaYelp },
        { type: 'venmo', label: 'Venmo', icon: FaMoneyBillWave },
        { type: 'paypal', label: 'PayPal', icon: FaPaypal },
        { type: 'cashapp', label: 'Cash App', icon: FaMoneyBillWave },
        { type: 'discord', label: 'Discord', icon: FaDiscord },
        { type: 'signal', label: 'Signal', icon: FaHashtag },
        { type: 'skype', label: 'Skype', icon: FaSkype },
        { type: 'telegram', label: 'Telegram', icon: FaTelegramPlane },
        { type: 'twitch', label: 'Twitch', icon: FaTwitch },
        { type: 'whatsapp', label: 'WhatsApp', icon: FaWhatsapp },
        { type: 'pronouns', label: 'Pronouns', icon: FaHashtag },
        { type: 'bio', label: 'Bio', icon: FaHeading },
        { type: 'portfolio', label: 'Portfolio', icon: FaLink },
    ];

    const addItem = (type: string, label: string) => {
        const items = card.items ?? [];
        let topPosition = items.length + 1;
        setCard({
            ...card,
            items: [
                ...items,
                {
                    type,
                    label,
                    value: '',
                    position: topPosition,
                    client_id: `${Date.now()}-${Math.random().toString(16).slice(2)}`,
                }
            ]
        });
        setOpen(false);
    }

    return (
        <Drawer open={open} onOpenChange={setOpen}>
            <DrawerContent className="bg-gray-100 px-6 py-4">
                <div className="absolute top-4 left-1/2 -translate-x-1/2 w-1/3 bg-gray-400 h-1 rounded-full cursor-grab" />
                <div className="w-full">
                    <div className="w-full flex justify-end">
                        <DrawerClose className="cursor-pointer">
                            <span className="text-gray-800">Done</span>
                        </DrawerClose>
                    </div>
                    <div className="w-full flex justify-center flex-col">
                        <span className="text-gray-800 font-semibold font-inter text-center">Select a field below to add it</span>
                        <div className="w-full grid grid-cols-3 gap-6 mt-6 overflow-y-auto h-72">
                            {options.map(opt => {
                                const Icon = opt.icon;
                                return (
                                    <button
                                        key={opt.type}
                                        onClick={() => addItem(opt.type, opt.label)}
                                        className="flex justify-center flex-col items-center hover:bg-gray-200 cursor-pointer p-2 rounded-lg"
                                    >
                                        <div className="bg-primary-500 rounded-full p-2">
                                            <Icon className="text-white" />
                                        </div>
                                        <span className="text-sm font-inter text-center">{opt.label}</span>
                                    </button>
                                );
                            })}
                        </div>
                    </div>
                </div>
            </DrawerContent>
        </Drawer>
    );
}

export default Options;
