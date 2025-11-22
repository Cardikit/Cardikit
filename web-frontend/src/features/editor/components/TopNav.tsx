import { MdModeEdit } from 'react-icons/md';
import { Link, useParams } from 'react-router-dom';
import type { CardType } from '@/types/card';
import { useCreateCard } from '@/features/editor/hooks/useCreateCard';
import { useUpdateCard } from '@/features/editor/hooks/useUpdateCard';
import { useNavigate } from 'react-router-dom';
import { fetchCsrfToken } from '@/lib/fetchCsrfToken';

interface TopNavProps {
    card: CardType;
    setOpen: (open: boolean) => void;
}

const TopNav: React.FC<TopNavProps> = ({ card, setOpen }) => {
    const { createCard } = useCreateCard();
    const { updateCard } = useUpdateCard();
    const navigate = useNavigate();
    const { id } = useParams();

    const onSubmit = async () => {
        try {
            await fetchCsrfToken();
            if (id) {
                await updateCard({
                    name: card.name,
                    card_items: card.items
                }, Number(id));
            } else {
                await createCard({
                    name: card.name,
                    card_items: card.items
                });
            }
            navigate('/dashboard');
        } catch (error) {
            console.error(error);
        }
    }

    return (
        <div className="fixed top-0 w-full z-10 p-4 flex items-center justify-between text-gray-800">
            <Link to="/dashboard" className="font-inter cursor-pointer">Cancel</Link>
            <div onClick={() => setOpen(true)} className="flex items-center space-x-2 cursor-pointer">
                <h1 className="text-xl font-semibold font-inter">{card.name}</h1>
                <MdModeEdit className="text-2xl" />
            </div>
            <p onClick={onSubmit} className="font-inter cursor-pointer">Save</p>
        </div>
    );
}

export default TopNav;
