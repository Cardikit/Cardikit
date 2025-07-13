import { useState, useEffect } from 'react';
import TopNav from '@/features/editor/components/TopNav';
import TitleEditor from '@/features/editor/components/TitleEditor';
import type { Card } from '@/types/card';

const defaultCard: Card = {
    name: 'New Card',
}

const Editor: React.FC = () => {
    const [titleEditorOpen, setTitleEditorOpen] = useState(false);
    const [card, setCard] = useState<Card>(defaultCard);

    useEffect(() => {
        setCard(defaultCard);
    }, []);

    return (
        <div className="h-dvh bg-gray-300 pt-16 overflow-hidden">
            <TopNav card={card} setOpen={setTitleEditorOpen} />
            <TitleEditor setCard={setCard} card={card} open={titleEditorOpen} setOpen={setTitleEditorOpen} />
        </div>
    );
}

export default Editor;
