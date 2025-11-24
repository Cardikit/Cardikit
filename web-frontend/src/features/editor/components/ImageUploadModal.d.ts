interface ImageUploadModalProps {
    open: boolean;
    onClose: () => void;
    onSave: (dataUrl: string | null) => void;
    title: string;
    allowClear?: boolean;
}
declare const ImageUploadModal: React.FC<ImageUploadModalProps>;
export default ImageUploadModal;
