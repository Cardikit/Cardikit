import { useEffect, useMemo, useState } from 'react';
import { FaDownload, FaEnvelope, FaPhone } from 'react-icons/fa';
import { contactService } from '@/services/contactService';
import { useFetchCards } from '@/features/dashboard/hooks/useFetchCards';
import type { Contact } from '@/types/contact';
import { extractErrorMessage } from '@/services/errorHandling';
import DesktopNav from '@/features/dashboard/components/DesktopNav';
import BottomNav from '@/features/dashboard/components/BottomNav';
import NavMenu from '@/features/dashboard/components/NavMenu';
import { MdOutlineMenu } from 'react-icons/md';

const formatDate = (value?: string | null) => {
    if (!value) return '—';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return value;
    return date.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
};

const normalizePhoneHref = (phone?: string | null) => {
    if (!phone) return null;
    const digits = phone.replace(/[^0-9+]/g, '');
    return digits ? `tel:${digits}` : null;
};

const Contacts: React.FC = () => {
    const [contacts, setContacts] = useState<Contact[]>([]);
    const [page, setPage] = useState(1);
    const [total, setTotal] = useState(0);
    const [perPage, setPerPage] = useState(30);
    const [cardFilter, setCardFilter] = useState<number | 'all'>('all');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [downloading, setDownloading] = useState(false);
    const [openMenu, setOpenMenu] = useState(false);

    const { cards, loading: cardsLoading } = useFetchCards();

    const totalPages = useMemo(() => Math.max(1, Math.ceil(total / perPage || 1)), [total, perPage]);

    useEffect(() => {
        setPage(1);
    }, [cardFilter]);

    useEffect(() => {
        let mounted = true;
        const fetchData = async () => {
            setLoading(true);
            setError(null);
            try {
                const response = await contactService.list(page, cardFilter === 'all' ? undefined : cardFilter);
                if (!mounted) return;
                setContacts(response.data);
                setTotal(response.total);
                setPerPage(response.per_page);
            } catch (err) {
                if (!mounted) return;
                setError(extractErrorMessage(err, 'Failed to load contacts'));
                setContacts([]);
                setTotal(0);
            } finally {
                if (mounted) setLoading(false);
            }
        };
        fetchData();
        return () => {
            mounted = false;
        };
    }, [page, cardFilter]);

    const handleExport = async () => {
        try {
            setDownloading(true);
            const blob = await contactService.exportCsv(cardFilter === 'all' ? undefined : cardFilter);
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'contacts.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        } catch (err) {
            setError(extractErrorMessage(err, 'Failed to download CSV'));
        } finally {
            setDownloading(false);
        }
    };

    return (
        <div className="min-h-dvh bg-gray-100 pt-16 md:pt-24">
            <DesktopNav />

            <div className="w-full h-full pb-20 px-4 lg:px-8 flex flex-col lg:w-3/4 lg:ml-auto max-w-6xl space-y-4">
                <header className="flex items-center justify-between flex-wrap gap-3">
                    <div className="flex items-center gap-3">
                        <MdOutlineMenu onClick={() => setOpenMenu(true)} className="text-3xl cursor-pointer lg:hidden" />
                        <div>
                            <p className="text-sm text-gray-500 font-inter">Contacts</p>
                            <h1 className="text-2xl md:text-3xl font-bold text-gray-900 font-inter">Shared contacts</h1>
                            <p className="text-sm text-gray-600">View contact details shared from your public cards.</p>
                        </div>
                    </div>
                </header>

                <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div className="flex flex-wrap items-center gap-3">
                        <div className="flex items-center gap-2">
                            <label htmlFor="card-filter" className="text-sm text-gray-600">Filter by card</label>
                            <select
                                id="card-filter"
                                value={cardFilter === 'all' ? 'all' : String(cardFilter)}
                                onChange={(e) => {
                                    const val = e.target.value;
                                    setCardFilter(val === 'all' ? 'all' : Number(val));
                                }}
                                className="border border-gray-300 rounded-md px-3 py-2 bg-white shadow-sm"
                                disabled={cardsLoading}
                            >
                                <option value="all">All cards</option>
                                {cards.map(card => (
                                    <option key={card.id} value={card.id}>{card.name}</option>
                                ))}
                            </select>
                        </div>
                        <button
                            onClick={handleExport}
                            disabled={downloading || loading}
                            className="inline-flex items-center gap-2 bg-primary-500 text-white px-4 py-2 rounded-md shadow hover:bg-primary-700 disabled:opacity-60"
                        >
                            <FaDownload />
                            {downloading ? 'Preparing...' : 'Download CSV'}
                        </button>
                    </div>
                </div>

                {error && (
                    <div className="bg-red-50 text-red-800 border border-red-200 rounded-md px-4 py-3">
                        {error}
                    </div>
                )}

                <div className="bg-white rounded-lg shadow overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                    <th className="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                    <th className="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Phone</th>
                                    <th className="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Card</th>
                                    <th className="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Source</th>
                                    <th className="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Received</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-200">
                                {loading ? (
                                    <tr>
                                        <td colSpan={6} className="px-4 py-6 text-center text-gray-500">Loading contacts...</td>
                                    </tr>
                                ) : contacts.length === 0 ? (
                                    <tr>
                                        <td colSpan={6} className="px-4 py-6 text-center text-gray-500">No contacts yet.</td>
                                    </tr>
                                ) : (
                                    contacts.map(contact => {
                                        const phoneHref = normalizePhoneHref(contact.phone);
                                        return (
                                            <tr key={contact.id} className="hover:bg-gray-50">
                                                <td className="px-4 py-3 text-sm text-gray-900">{contact.name || '—'}</td>
                                                <td className="px-4 py-3 text-sm">
                                                    {contact.email ? (
                                                        <a className="text-primary-700 hover:underline inline-flex items-center gap-2" href={`mailto:${contact.email}`}>
                                                            <FaEnvelope /> {contact.email}
                                                        </a>
                                                    ) : '—'}
                                                </td>
                                                <td className="px-4 py-3 text-sm">
                                                    {contact.phone ? (
                                                        phoneHref ? (
                                                            <a className="text-primary-700 hover:underline inline-flex items-center gap-2" href={phoneHref}>
                                                                <FaPhone /> {contact.phone}
                                                            </a>
                                                        ) : contact.phone
                                                    ) : '—'}
                                                </td>
                                                <td className="px-4 py-3 text-sm text-gray-800">{contact.card_name || contact.card_slug || '—'}</td>
                                                <td className="px-4 py-3 text-sm">
                                                    {contact.source_url ? (
                                                        <a className="text-primary-700 hover:underline" href={contact.source_url} target="_blank" rel="noreferrer">
                                                            {contact.source_url.replace(/^https?:\/\//, '').slice(0, 40)}
                                                            {contact.source_url.length > 40 ? '…' : ''}
                                                        </a>
                                                    ) : '—'}
                                                </td>
                                                <td className="px-4 py-3 text-sm text-gray-700">{formatDate(contact.created_at)}</td>
                                            </tr>
                                        );
                                    })
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="flex items-center justify-between text-sm text-gray-700">
                    <div>
                        Showing {(contacts.length && ((page - 1) * perPage + 1)) || 0}
                        {' - '}
                        {(page - 1) * perPage + contacts.length} of {total}
                    </div>
                    <div className="flex items-center gap-2">
                        <button
                            onClick={() => setPage(prev => Math.max(1, prev - 1))}
                            disabled={page === 1 || loading}
                            className="px-3 py-1 rounded border border-gray-300 bg-white disabled:opacity-50"
                        >
                            Prev
                        </button>
                        <span className="px-2">
                            Page {page} of {totalPages}
                        </span>
                        <button
                            onClick={() => setPage(prev => Math.min(totalPages, prev + 1))}
                            disabled={page >= totalPages || loading}
                            className="px-3 py-1 rounded border border-gray-300 bg-white disabled:opacity-50"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>
            <BottomNav />
            <NavMenu open={openMenu} closeMenu={() => setOpenMenu(false)} />
        </div>
    );
};

export default Contacts;
