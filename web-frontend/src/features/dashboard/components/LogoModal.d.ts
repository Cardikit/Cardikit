import type { CardType } from '@/types/card';
interface LogoModalProps {
    currentCard: CardType;
    refreshCards: () => Promise<void>;
    setLogoModalOpen: (open: boolean) => void;
}
declare const LogoModal: React.FC<LogoModalProps>;
export default LogoModal;
