import type { CardType } from '@/types/card';
interface TopNavProps {
    card: CardType;
    setOpen: (open: boolean) => void;
    formError: string | null;
    setFormError: (error: string | null) => void;
    setItemErrors: (errors: Record<string, string>) => void;
}
declare const TopNav: React.FC<TopNavProps>;
export default TopNav;
