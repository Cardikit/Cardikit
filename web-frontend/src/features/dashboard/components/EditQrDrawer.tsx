import {
    Drawer,
    DrawerClose,
    DrawerContent,
} from '@/components/ui/drawer';
import type { CardType } from '@/types/card';
import { useState } from 'react';

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
    setLogoModalOpen: (open: boolean) => void
}

const EditQrDrawer: React.FC<EditQrDrawerProps> = ({ open, setOpen, currentCard, setLogoModalOpen }) => {
    const [copied, setCopied] = useState(false);

    const cardUrl = currentCard.qr_url ?? currentCard.qr_image ?? '';
    const qrImageUrl = currentCard.qr_image ?? '';

    const copyLink = async () => {
        if (!cardUrl) return;
        try {
            await navigator.clipboard.writeText(cardUrl);
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        } catch (e) {
            console.error('Failed to copy link', e);
        }
    };

    const shareLink = async () => {
        if (!cardUrl) return;
        if (navigator.share) {
            try {
                await navigator.share({ title: currentCard.name, url: cardUrl });
            } catch (e) {
                // ignored if user cancels
            }
        } else {
            await copyLink();
        }
    };

    const shareSms = async () => {
        if (!cardUrl) return;
        const smsUrl = `sms:?&body=${encodeURIComponent(cardUrl)}`;
        window.open(smsUrl, '_blank');
    };

    const shareEmail = async () => {
        if (!cardUrl) return;
        const mailto = `mailto:?subject=${encodeURIComponent(currentCard.name || 'My card')}&body=${encodeURIComponent(cardUrl)}`;
        window.open(mailto, '_blank');
    };

    const downloadQr = async () => {
        if (!qrImageUrl) return;
        const link = document.createElement('a');
        link.href = qrImageUrl;
        link.target = '_blank';
        link.download = `card-${currentCard.id}-qr.png`;
        link.click();
    };

    const shareQr = async () => {
        if (!qrImageUrl) return;
        try {
            const res = await fetch(qrImageUrl);
            const blob = await res.blob();
            const file = new File([blob], `card-${currentCard.id}-qr.png`, { type: blob.type || 'image/png' });
            const canShareFiles = typeof (navigator as any).canShare === 'function'
                ? (navigator as any).canShare({ files: [file] })
                : false;

            if (canShareFiles && typeof (navigator as any).share === 'function') {
                await (navigator as any).share({ files: [file], title: currentCard.name });
                return;
            }
        } catch (e) {
            // fall through to download
        }
        await downloadQr();
    };


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

                        <button
                            onClick={() => {setLogoModalOpen(true), setOpen(false)}}
                            className="bg-primary-500 text-gray-100 py-2 w-full rounded-lg font-semibold flex items-center justify-center space-x-2 cursor-pointer hover:bg-primary-900"
                        >
                            <FaImage />
                            <span>Add logo to QR Code</span>
                        </button>

                        <button onClick={copyLink} className="bg-primary-500 text-gray-100 py-2 w-full rounded-lg font-semibold flex items-center justify-center space-x-2 cursor-pointer hover:bg-primary-900">
                            <FaLink />
                            <span>{copied ? 'Copied!' : 'Copy link'}</span>
                        </button>

                        <button
                            onClick={shareSms}
                            className="bg-primary-500 text-gray-100 py-2 w-full rounded-lg font-semibold flex items-center justify-center space-x-2 cursor-pointer hover:bg-primary-900"
                        >
                            <FaSms />
                            <span>Text your card</span>
                        </button>

                        <button
                            onClick={shareEmail}
                            className="bg-primary-500 text-gray-100 py-2 w-full rounded-lg font-semibold flex items-center justify-center space-x-2 cursor-pointer hover:bg-primary-900"
                        >
                            <FaEnvelope />
                            <span>Email your card</span>
                        </button>

                        <button
                            onClick={shareLink}
                            className="bg-primary-500 text-gray-100 py-2 w-full rounded-lg font-semibold flex items-center justify-center space-x-2 cursor-pointer hover:bg-primary-900"
                        >
                            <FaShareAlt />
                            <span>Send another way</span>
                        </button>

                        <button
                            onClick={downloadQr}
                            className="bg-primary-500 text-gray-100 py-2 w-full rounded-lg font-semibold flex items-center justify-center space-x-2 cursor-pointer hover:bg-primary-900"
                        >
                            <FaSave />
                            <span>Save QR code to photos</span>
                        </button>

                        <button
                            onClick={shareQr}
                            className="bg-primary-500 text-gray-100 py-2 w-full rounded-lg font-semibold flex items-center justify-center space-x-2 cursor-pointer hover:bg-primary-900"
                        >
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
