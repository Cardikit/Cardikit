import {
    Drawer,
    DrawerClose,
    DrawerContent,
} from '@/components/ui/drawer';
import type { CardType } from '@/types/card';

import {
    FaImage,
    FaLink,
    FaSms,
    FaEnvelope,
    FaShareAlt,
    FaSave,
    FaQrcode,
} from "react-icons/fa";

interface EditQrDrawerProps {
    open: boolean;
    setOpen: (open: boolean) => void;
    currentCard: CardType;
}

const EditQrDrawer: React.FC<EditQrDrawerProps> = ({ open, setOpen, currentCard }) => {
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

                    <div className="flex flex-col items-center space-y-4 h-96 overflow-y-scroll mt-4">

                        <div className="bg-white rounded-xl shadow p-4 flex flex-col items-center space-y-2">
                            <img
                                src={currentCard.qr_image}
                                alt={`QR for ${currentCard.name}`}
                                className="h-52 w-52 object-contain"
                            />
                        </div>

                        <p className="text-sm text-gray-600 font-inter text-center">
                            Scan to view card
                        </p>

                        <button className="bg-primary-500 text-gray-100 py-2 w-full rounded-lg font-semibold flex items-center justify-center space-x-2 cursor-pointer hover:bg-primary-900">
                            <FaImage />
                            <span>Add logo to QR Code</span>
                        </button>

                        <button className="bg-primary-500 text-gray-100 py-2 w-full rounded-lg font-semibold flex items-center justify-center space-x-2 cursor-pointer hover:bg-primary-900">
                            <FaLink />
                            <span>Copy link</span>
                        </button>

                        <button className="bg-primary-500 text-gray-100 py-2 w-full rounded-lg font-semibold flex items-center justify-center space-x-2 cursor-pointer hover:bg-primary-900">
                            <FaSms />
                            <span>Text your card</span>
                        </button>

                        <button className="bg-primary-500 text-gray-100 py-2 w-full rounded-lg font-semibold flex items-center justify-center space-x-2 cursor-pointer hover:bg-primary-900">
                            <FaEnvelope />
                            <span>Email your card</span>
                        </button>

                        <button className="bg-primary-500 text-gray-100 py-2 w-full rounded-lg font-semibold flex items-center justify-center space-x-2 cursor-pointer hover:bg-primary-900">
                            <FaShareAlt />
                            <span>Send another way</span>
                        </button>

                        <button className="bg-primary-500 text-gray-100 py-2 w-full rounded-lg font-semibold flex items-center justify-center space-x-2 cursor-pointer hover:bg-primary-900">
                            <FaSave />
                            <span>Save QR code to photos</span>
                        </button>

                        <button className="bg-primary-500 text-gray-100 py-2 w-full rounded-lg font-semibold flex items-center justify-center space-x-2 cursor-pointer hover:bg-primary-900">
                            <FaQrcode />
                            <span>Send QR code</span>
                        </button>

                    </div>
                </div>
            </DrawerContent>
        </Drawer>
    );
}

export default EditQrDrawer;
