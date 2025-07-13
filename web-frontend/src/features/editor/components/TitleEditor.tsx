import {
    Drawer,
    DrawerClose,
    DrawerContent,
} from '@/components/ui/drawer';
import Input from '@/features/auth/components/Input';
import { FaIdCard } from 'react-icons/fa';
import type { Card } from '@/types/card';

interface TitleEditorProps {
    open: boolean;
    setOpen: (open: boolean) => void;
    card: Card;
    setCard: (card: Card) => void;
}

const TitleEditor: React.FC<TitleEditorProps> = ({ open, setOpen, card, setCard }) => {
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
                    <label htmlFor="name" className="block mb-2 text-sm font-medium text-gray-800">Edit card name</label>
                    <Input
                        id="name"
                        type="text"
                        placeholder="Card name"
                        className="w-full"
                        value={card.name}
                        onChange={(e) => setCard({ ...card, name: e.target.value })}
                        startAdornment={<FaIdCard className="text-primary-500" />}
                        autoFocus
                        onKeyDown={(e) => {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                setOpen(false);
                            }
                        }}
                    />
                </div>
            </DrawerContent>
        </Drawer>
    );
}

export default TitleEditor;
