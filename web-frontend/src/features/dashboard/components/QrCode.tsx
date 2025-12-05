import type { CardType } from '@/types/card';

interface QrCodeProps {
    currentCard: CardType;
    loading: boolean;
    setOpen: (open: boolean) => void
}

/**
 * QrCode
 * ------
 * Displays the QR code for the currently selected card and provides access
 * to the full QR management drawer.
 *
 * Responsibilities:
 * - Show a loading placeholder while QR data is being fetched.
 * - Render the card's QR code when available:
 *   - Wrapped in a clickable container that opens the QR actions drawer
 *     via `setOpen(true)`.
 *   - Adds a timestamp query (`?t=...`) to the `<img>` to ensure the QR
 *     image is always fresh and avoids browser caching issues.
 * - Provide a friendly fallback when no QR code exists for the card.
 *
 * UI details:
 * - Centers content both vertically and horizontally.
 * - Uses a white rounded card-style container for both loaded and fallback states.
 * - Includes a “Scan to view card” hint under the QR code for clarity.
 *
 * Props:
 * - `currentCard`: The card whose QR code should be displayed.
 * - `loading`: When true, shows a pulsing skeleton block.
 * - `setOpen`: Opens the EditQrDrawer when the QR image is clicked.
 *
 * @component
 * @since 0.0.2
 */
const QrCode: React.FC<QrCodeProps> = ({ currentCard, loading, setOpen }) => {

    return (
        <div className="flex-grow flex items-center justify-center">
            {loading ? (
                <div className="animate-pulse h-56 w-56 bg-gray-200 rounded-xl" />
            ) : currentCard?.qr_image ? (
                <div onClick={() => setOpen(true)} className="bg-white rounded-xl shadow p-4 flex flex-col items-center space-y-2 cursor-pointer">
                    <img
                        src={`${currentCard.qr_image}?t=${Date.now()}`}
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
