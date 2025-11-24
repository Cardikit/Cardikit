import type { CardType } from '@/types/card';
import { getItemConfig } from '@/features/dashboard/config/itemConfig';

interface CardProps {
    card: CardType
}

const Card: React.FC<CardProps> = ({ card }) => {
    const accentColor = card.color ?? '#1D4ED8';
    const banner = card.banner_image ?? null;
    const avatar = card.avatar_image ?? null;

    return (
        <div className="p-10">
            <div className="flex bg-white rounded-xl shadow h-[600px] w-full p-4 flex-col space-y-3">
                <div className="w-full mb-2">
                    <div
                        className="w-full h-32 rounded-lg bg-gray-100 overflow-hidden"
                        style={{ backgroundColor: banner ? undefined : accentColor + '22' }}
                    >
                        {banner && <img src={banner} alt="Card banner" className="w-full h-full object-cover" />}
                    </div>
                    <div className="w-full flex justify-center -mt-10">
                        <div
                            className="w-20 h-20 rounded-full bg-gray-200 border-4 border-white overflow-hidden shadow flex items-center justify-center"
                            style={{ backgroundColor: avatar ? undefined : accentColor + '44' }}
                        >
                            {avatar ? (
                                <img src={avatar} alt="Card avatar" className="w-full h-full object-cover" />
                            ) : (
                                <span className="text-gray-500 text-sm font-inter">Avatar</span>
                            )}
                        </div>
                    </div>
                </div>
                {card.items?.map((item, index) => {
                    const config = getItemConfig(item.type);
                    const Icon = config.icon;
                    const hasLabel = config.fields.label && item.label;
                    const primaryText = hasLabel ? item.label : item.value;
                    const secondaryText = hasLabel ? item.value : undefined;
                    const key = item.id ?? item.client_id ?? index;

                    const iconColorClass = config.iconClass ?? 'text-white';
                    const content = (
                        <div className="flex items-start space-x-3">
                            <div
                                className="rounded-full p-2 flex items-center justify-center"
                                style={{ backgroundColor: accentColor }}
                            >
                                <Icon className={iconColorClass} />
                            </div>
                            <div className="flex flex-col">
                                <span className="font-semibold font-inter text-lg leading-tight break-all">
                                    {primaryText}
                                </span>
                                {secondaryText && (
                                    <span className="text-sm text-gray-600 font-inter break-all">
                                        {secondaryText}
                                    </span>
                                )}
                            </div>
                        </div>
                    );

                    if (config.fields.link) {
                        return (
                            <a
                                key={key}
                                href={item.value}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="w-full rounded-lg hover:bg-gray-100 transition-colors p-2 block"
                            >
                                {content}
                            </a>
                        );
                    }

                    return (
                        <div key={key} className="w-full rounded-lg p-2">
                            {content}
                        </div>
                    );
                })}
            </div>
        </div>
    );
}

export default Card;
