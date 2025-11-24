import type { CardType } from '@/types/card';
interface OptionsProps {
    open: boolean;
    setOpen: (open: boolean) => void;
    card: CardType;
    setCard: (card: CardType) => void;
}
declare const Options: React.FC<OptionsProps>;
export default Options;
