import { useState } from 'react';
import type { CardType } from '@/types/card';
import { FaTimes } from 'react-icons/fa';
import { cardService } from '@/services/cardService';
import { extractErrorMessage } from '@/services/errorHandling';

/**
 * LogoModal
 * ---------
 * Modal UI for uploading a logo and regenerating a card's QR code.
 * - Validates file type/size and previews selection.
 * - Sends base64 payload to cardService.regenerateQr, then refreshes cards.
 * - Manages upload/generate state and surfaces error messaging.
 *
 * @since 0.0.2
 */
interface LogoModalProps {
    currentCard: CardType
    refreshCards: () => Promise<void>;
    setLogoModalOpen: (open: boolean) => void
}

const LogoModal: React.FC<LogoModalProps> = ({ refreshCards, currentCard, setLogoModalOpen }) => {
    const [logoPreview, setLogoPreview] = useState<string | null>(null);
    const [uploadError, setUploadError] = useState<string | null>(null);
    const [uploading, setUploading] = useState(false);
    const [generating, setGenerating] = useState(false);

    const resetLogoModal = () => {
        setLogoPreview(null);
        setUploadError(null);
        setUploading(false);
        setGenerating(false);
    };

    const closeLogoModal = () => {
        resetLogoModal();
        setLogoModalOpen(false);
    };

    const onFileChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (!file) return;
        setUploadError(null);

        if (!file.type.startsWith('image/')) {
            setUploadError('Please upload an image file (png, jpg, svg).');
            return;
        }

        const maxBytes = 2 * 1024 * 1024; // 2MB
        if (file.size > maxBytes) {
            setUploadError('Image must be 2MB or smaller.');
            return;
        }

        setUploading(true);

        const reader = new FileReader();
        reader.onloadend = () => {
            setLogoPreview(reader.result as string);
            setUploading(false);
        };
        reader.onerror = () => {
            setUploadError('Failed to read the image. Please try again.');
            setUploading(false);
        };
        reader.readAsDataURL(file);
    };

    const submitLogo = async () => {
        if (!currentCard?.id || !logoPreview) {
            setUploadError('Please select an image to upload.');
            return;
        }

        setGenerating(true);
        setUploadError(null);

        try {
            const base64 = logoPreview.replace(/^data:image\/[a-zA-Z]+;base64,/, '');
            await cardService.regenerateQr(currentCard.id, base64);
            await refreshCards();
            closeLogoModal();
        } catch (err: any) {
            setUploadError(extractErrorMessage(err, 'Failed to generate QR code'));
        } finally {
            setGenerating(false);
        }
    };


    return (
        <div
            className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
            onClick={closeLogoModal}
        >
            <div
                className="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative"
                onClick={(e) => e.stopPropagation()}
            >
                <button
                    onClick={closeLogoModal}
                    className="absolute top-3 right-3 text-gray-500 hover:text-gray-700 cursor-pointer"
                >
                    <FaTimes />
                </button>
                <h3 className="text-lg font-semibold mb-2">Add a logo to your QR</h3>
                <p className="text-sm text-gray-600 mb-4">
                    Upload a square PNG/JPG up to 2MB. The logo will be centered inside your QR code.
                </p>

                <div className="border-2 border-dashed border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center space-y-2">
                    {logoPreview ? (
                        <img
                            src={logoPreview}
                            alt="Logo preview"
                            className="h-24 w-24 object-contain"
                        />
                    ) : (
                        <span className="text-gray-500 text-sm">No image selected</span>
                    )}
                    <label className="bg-primary-500 text-white px-4 py-2 rounded cursor-pointer hover:bg-primary-900">
                        {uploading ? 'Uploading...' : 'Choose image'}
                        <input
                            type="file"
                            accept="image/*"
                            onChange={onFileChange}
                            className="hidden"
                            disabled={uploading || generating}
                        />
                    </label>
                </div>

                {uploadError && <p className="text-red-600 text-sm mt-2">{uploadError}</p>}

                <div className="mt-4 flex items-center justify-end space-x-3">
                    <button
                        onClick={closeLogoModal}
                        className="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100 cursor-pointer"
                        disabled={generating}
                    >
                        Cancel
                    </button>
                    <button
                        onClick={submitLogo}
                        disabled={uploading || generating || !logoPreview}
                        className="px-4 py-2 rounded bg-primary-500 text-white font-semibold hover:bg-primary-900 disabled:opacity-60 cursor-pointer"
                    >
                        {generating ? 'Generating...' : 'Save'}
                    </button>
                </div>
            </div>
        </div>
    );
}

export default LogoModal;
