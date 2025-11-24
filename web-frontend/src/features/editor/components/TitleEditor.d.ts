import type { CardType } from '@/types/card';
interface TitleEditorProps {
    open: boolean;
    setOpen: (open: boolean) => void;
    card: CardType;
    setCard: (card: CardType) => void;
}
declare const TitleEditor: React.FC<TitleEditorProps>;
export default TitleEditor;
