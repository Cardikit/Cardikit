import type { CardType } from '@/types/card';

interface QrCodeProps {
    currentCard: CardType;
    loading: boolean;
    setOpen: (open: boolean) => void
}

const QrCode: React.FC<QrCodeProps> = ({ currentCard, loading, setOpen }) => {

    const openQrDrawer = () => {
        if (currentCard?.qr_image) {
            setOpen(true);
        }
    }

    return (
        <div onClick={openQrDrawer} className="flex-grow flex items-center justify-center cursor-pointer">
            {loading ? (
                <div className="animate-pulse h-56 w-56 bg-gray-200 rounded-xl" />
            ) : currentCard?.qr_image ? (
                <div className="bg-white rounded-xl shadow p-4 flex flex-col items-center space-y-2">
                    <img
                        src={currentCard.qr_image}
                        alt={`QR for ${currentCard.name}`}
                        className="h-52 w-52 object-contain"
                    />
                    <p className="text-sm text-gray-600 font-inter text-center">
                        Scan to view card
                    </p>
                </div>
            ) : (
                <div className="bg-white rounded-xl shadow p-4 flex flex-col justify-center items-center space-y-2">
                    <div className="size-52 flex items-center justify-center">
                        <p className="text-gray-600 font-inter text-center">No QR code available</p>
                    </div>
                    <p className="text-sm text-gray-600 font-inter text-center">
                        Scan to view card
                    </p>
                </div>
            )}
        </div>
    );
}

export default QrCode;
