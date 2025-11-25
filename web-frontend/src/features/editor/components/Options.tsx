import {
    Drawer,
    DrawerClose,
    DrawerContent,
} from '@/components/ui/drawer';
import { getItemConfig, ITEM_ORDER } from '@/features/editor/config/itemConfig';
import type { CardType } from '@/types/card';

interface OptionsProps {
    open: boolean;
    setOpen: (open: boolean) => void;
    card: CardType;
    setCard: (card: CardType) => void;
}

const Options: React.FC<OptionsProps> = ({ open, setOpen, card, setCard }) => {

    const addItem = (type: string) => {
        const items = card.items ?? [];
        let topPosition = items.length + 1;
        const config = getItemConfig(type);
        const includesLabel = config.fields.some(f => f.key === 'label');

        const newItem = {
            type,
            value: '',
            position: topPosition,
            client_id: `${Date.now()}-${Math.random().toString(16).slice(2)}`,
            ...(includesLabel ? { label: '' } : {}),
        };

        setCard({
            ...card,
            items: [
                ...items,
                newItem,
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
                        <div className="w-full grid grid-cols-3 gap-6 mt-6 overflow-y-auto h-72 md:h-96">
                            {ITEM_ORDER.map(type => {
                                const config = getItemConfig(type);
                                const Icon = config.icon;
                                const fieldLabels = config.fields.map(f => f.label).join(' + ');
                                return (
                                    <button
                                        key={type}
                                        onClick={() => addItem(type)}
                                        className="flex justify-center flex-col items-center hover:bg-gray-200 cursor-pointer p-2 rounded-lg"
                                    >
                                        <div className={`${config.accentClass} rounded-full p-2`}>
                                            <Icon className={config.iconClass ?? 'text-white'} />
                                        </div>
                                        <span className="text-sm font-inter text-center">{config.displayName}</span>
                                        <span className="text-[11px] text-gray-500 font-inter text-center">{fieldLabels}</span>
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
