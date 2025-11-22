import {
    Drawer,
    DrawerClose,
    DrawerContent,
} from '@/components/ui/drawer';
import type { CardType } from '@/types/card';
import { FaUser } from 'react-icons/fa';

interface OptionsProps {
    open: boolean;
    setOpen: (open: boolean) => void;
    card: CardType;
    setCard: (card: CardType) => void;
}

const Options: React.FC<OptionsProps> = ({ open, setOpen, card, setCard }) => {

    const addName = () => {
        const items = card.items ?? [];
        let topPosition = items.length + 1;
        setCard({
            ...card,
            items: [
                ...items,
                {
                    type: 'name',
                    value: '',
                    position: topPosition,
                    client_id: crypto.randomUUID(),
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
                        <div className="w-full grid grid-cols-3 gap-6 mt-6">
                            <div onClick={addName} className="flex justify-center flex-col items-center hover:bg-gray-200 cursor-pointer p-2 rounded-lg">
                                <div className="bg-primary-500 rounded-full p-2">
                                    <FaUser className="text-white" />
                                </div>
                                <span className="text-sm font-inter">Name</span>
                            </div>
                        </div>
                    </div>
                </div>
            </DrawerContent>
        </Drawer>
    );
}

export default Options;
