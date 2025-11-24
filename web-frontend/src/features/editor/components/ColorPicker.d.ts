import type { CardType } from '@/types/card';
interface ColorPickerProps {
    card: CardType;
    setCard: React.Dispatch<React.SetStateAction<CardType>>;
}
declare const ColorPicker: React.FC<ColorPickerProps>;
export default ColorPicker;
