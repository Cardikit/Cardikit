import type { CardType } from '@/types/card';
interface EditQrDrawerProps {
    open: boolean;
    setOpen: (open: boolean) => void;
    currentCard: CardType;
    setLogoModalOpen: (open: boolean) => void;
}
declare const EditQrDrawer: React.FC<EditQrDrawerProps>;
export default EditQrDrawer;
