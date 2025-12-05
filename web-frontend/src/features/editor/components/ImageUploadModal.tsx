import {
    Drawer,
    DrawerContent,
    DrawerClose,
} from '@/components/ui/drawer';
import { useRef, useState } from 'react';
interface ImageUploadModalProps {
    open: boolean;
    onClose: () => void;
    onSave: (dataUrl: string | null) => void;
    title: string;
    allowClear?: boolean;
}

/**
 * ImageUploadModal
 * ----------------
 * A bottom drawer used for uploading, validating, and optionally removing
 * images (banner, avatar, QR logo, etc.) within the Cardikit editor.
 *
 * Responsibilities:
 * - Provide an accessible file picker UI inside a Drawer component.
 * - Validate selected files before processing:
 *   - Accepts only PNG, JPEG, and WEBP formats.
 *   - Enforces a 5MB maximum file size.
 * - Convert valid files into base64 data URLs via FileReader, then:
 *   - Pass the result to `onSave`.
 *   - Close the modal afterward.
 * - Allow clearing/removing the image when `allowClear` is true.
 * - Display error messages for invalid file type or size violations.
 *
 * Drawer behavior:
 * - Controlled by the `open` prop.
 * - When the Drawer closes (via swipe or clicking outside), `onClose` fires.
 * - Includes a “Done” button (`DrawerClose`) as an additional closing mechanism.
 *
 * UI details:
 * - File input is hidden; user triggers uploads via an `Upload image` button.
 * - Uses a visually centered drag bar, padding, and responsive spacing.
 * - Supports large-screen layouts with wider padding (`lg:px-72`).
 *
 * Props:
 * - `open`       → Whether the drawer is visible.
 * - `onClose`    → Fired when the modal should close.
 * - `onSave`     → Called with the selected image's data URL, or `null` if removed.
 * - `title`      → Title displayed at the top (“Edit banner”, “Edit avatar”, etc.).
 * - `allowClear` → Whether to show the “Remove image” action (default: true).
 *
 * @component
 * @since 0.0.2
 */
const ImageUploadModal: React.FC<ImageUploadModalProps> = ({ open, onClose, onSave, title, allowClear = true }) => {
    const inputRef = useRef<HTMLInputElement | null>(null);
    const [error, setError] = useState<string | null>(null);

    const onFileChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (!file) return;

        if (!['image/png', 'image/jpeg', 'image/webp'].includes(file.type)) {
            setError('Only PNG, JPG, and WEBP are allowed');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            setError('Max size is 5MB');
            return;
        }

        const reader = new FileReader();
        reader.onload = () => {
            const result = reader.result as string;
            onSave(result);
            onClose();
        };
        reader.readAsDataURL(file);
    };

    const onRemove = () => {
        onSave(null);
        onClose();
    };

    return (
        <Drawer open={open} onOpenChange={(o) => !o && onClose()}>
            <DrawerContent className="bg-gray-100 px-6 py-4 lg:px-72">
                <div className="absolute top-4 left-1/2 -translate-x-1/2 w-1/3 bg-gray-400 h-1 rounded-full cursor-grab" />
                <div className="w-full">
                    <div className="w-full flex justify-end">
                        <DrawerClose className="cursor-pointer">
                            <span className="text-gray-800">Done</span>
                        </DrawerClose>
                    </div>
                    <div className="space-y-4 flex flex-col items-center">
                        <h2 className="text-lg font-semibold font-inter text-gray-800 w-full">{title}</h2>
                        <input
                            ref={inputRef}
                            type="file"
                            accept="image/png,image/jpeg,image/webp"
                            className="hidden"
                            onChange={onFileChange}
                        />
                        <button
                            type="button"
                            onClick={() => inputRef.current?.click()}
                            className="w-full md:w-3/4 bg-white border border-gray-300 rounded-lg py-3 text-center cursor-pointer hover:bg-gray-50 font-inter"
                        >
                            Upload image
                        </button>
                        {allowClear && (
                            <button
                                type="button"
                                onClick={onRemove}
                                className="w-full md:w-3/4 bg-white border border-red-400 text-red-600 rounded-lg py-3 text-center cursor-pointer hover:bg-red-50 font-inter"
                            >
                                Remove image
                            </button>
                        )}
                        {error && <p className="text-red-600 text-sm font-inter">{error}</p>}
                    </div>
                </div>
            </DrawerContent>
        </Drawer>
    );
};

export default ImageUploadModal;
