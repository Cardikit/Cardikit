import type { CardType } from '@/types/card';
interface QrCodeProps {
    currentCard: CardType;
    loading: boolean;
    setOpen: (open: boolean) => void;
}
declare const QrCode: React.FC<QrCodeProps>;
export default QrCode;
