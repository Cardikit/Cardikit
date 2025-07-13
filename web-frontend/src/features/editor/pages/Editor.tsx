import { useState } from 'react';
import TopNav from '@/features/editor/components/TopNav';
import TitleEditor from '@/features/editor/components/TitleEditor';
import { useParams, useNavigate } from 'react-router-dom';
import { useFetchCard } from '@/features/editor/hooks/useFetchCard';
import { useDeleteCard } from '@/features/editor/hooks/useDeleteCard';
import { fetchCsrfToken } from '@/lib/fetchCsrfToken';

const Editor: React.FC = () => {
    const [titleEditorOpen, setTitleEditorOpen] = useState(false);
    const { id } = useParams();
    const { card, setCard, loading } = useFetchCard(id ? Number(id) : undefined);
    const { deleteCard } = useDeleteCard();
    const navigate = useNavigate();

    const onDelete = async () => {
        try {
            await fetchCsrfToken();
            await deleteCard(card.id);
            navigate('/dashboard');
        } catch (error) {
            console.error('Error deleting card:', error);
        }
    }

    return (
        <div className="h-dvh bg-gray-300 pt-16 overflow-hidden">
            <TopNav card={card} setOpen={setTitleEditorOpen} />
            <TitleEditor setCard={setCard} card={card} open={titleEditorOpen} setOpen={setTitleEditorOpen} />
            <button onClick={onDelete} className="bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg cursor-pointer">Delete</button>
        </div>
    );
}

export default Editor;
