import type { CardType } from '@/types/card';
interface TopNavProps {
    openMenu: () => void;
    card: CardType;
    loading: boolean;
}
declare const TopNav: React.FC<TopNavProps>;
export default TopNav;
