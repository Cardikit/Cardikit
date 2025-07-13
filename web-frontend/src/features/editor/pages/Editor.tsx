import { useState } from 'react';
import TopNav from '@/features/editor/components/TopNav';
import TitleEditor from '@/features/editor/components/TitleEditor';
import { useParams } from 'react-router-dom';
import { useFetchCard } from '@/features/editor/hooks/useFetchCard';

const Editor: React.FC = () => {
    const [titleEditorOpen, setTitleEditorOpen] = useState(false);
    const { id } = useParams();
    const { card, setCard, loading } = useFetchCard(id ? Number(id) : undefined);

    return (
        <div className="h-dvh bg-gray-300 pt-16 overflow-hidden">
            <TopNav card={card} setOpen={setTitleEditorOpen} />
            <TitleEditor setCard={setCard} card={card} open={titleEditorOpen} setOpen={setTitleEditorOpen} />
        </div>
    );
}

export default Editor;
