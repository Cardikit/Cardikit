import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { fetchAnalyticsSummary } from '@/services/analytics';
import type { AnalyticsSummaryResponse } from '@/types/analytics';
import BottomNav from '@/features/dashboard/components/BottomNav';
import NavMenu from '@/features/dashboard/components/NavMenu';
import DesktopNav from '@/features/dashboard/components/DesktopNav';
import { MdOutlineMenu } from 'react-icons/md';
import { useFetchCards } from '@/features/dashboard/hooks/useFetchCards';
import StatCard from '@/features/analytics/components/StatCard';
import CardDropdown from '@/features/analytics/components/CardDropdown';

const ranges = [
    { label: 'Today', days: 1 },
    { label: '7d', days: 7 },
    { label: '30d', days: 30 },
    { label: '90d', days: 90 },
    { label: 'Year', days: 365 },
    { label: 'All time', days: null },
];

const Analytics: React.FC = () => {
    const [openMenu, setOpenMenu] = useState(false);
    const [range, setRange] = useState<{ label: string; days: number | null }>(ranges[2]);
    const [data, setData] = useState<AnalyticsSummaryResponse | null>(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [selectedCard, setSelectedCard] = useState<number | null>(null);
    const { cards } = useFetchCards();

    const load = async (days: number | null, cardId: number | null) => {
        setLoading(true);
        setError(null);
        try {
            const res = await fetchAnalyticsSummary({ days: days ?? undefined, cardId: cardId ?? undefined });
            setData(res);
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Failed to load analytics');
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        load(range.days, selectedCard);
    }, [range, selectedCard]);

    const totals = data?.totals ?? {
        views: 0,
        new_views: 0,
        returning_views: 0,
        clicks: 0,
        qr_scans: 0,
        nfc_scans: 0,
    };

    return (
        <div className="min-h-dvh bg-gray-300 pt-16 md:pt-24 overflow-hidden">
            {/* Desktop nav */}
            <DesktopNav />

            <div className="w-full h-full pb-20 px-4 lg:px-8 flex flex-col lg:w-3/4 lg:ml-auto">
                <header className="flex items-center justify-between mb-6">
                    <div className="flex items-center gap-3">
                        <MdOutlineMenu onClick={() => setOpenMenu(true)} className="text-3xl cursor-pointer lg:hidden" />
                        <div>
                            <p className="text-sm text-gray-700 uppercase tracking-wide font-semibold">Insights</p>
                            <h1 className="text-2xl md:text-3xl font-bold text-gray-900 font-inter">Analytics</h1>
                        </div>
                    </div>
                    <Link
                        to="/"
                        className="bg-white border border-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold shadow-sm hover:bg-gray-100 transition-colors"
                    >
                        Back to dashboard
                    </Link>
                </header>

                <div className="bg-white rounded-xl shadow p-4 md:p-6 space-y-4">
                    <div className="flex items-center justify-between gap-4 flex-wrap">
                        <div className="flex flex-col gap-2">
                            <p className="text-sm text-gray-600">Viewing last</p>
                            <div className="flex gap-2 mt-2 flex-wrap">
                                {ranges.map(option => (
                                    <button
                                        key={option.label}
                                        onClick={() => setRange(option)}
                                        className={`px-3 py-1 rounded-lg text-sm font-semibold ${
                                            range.label === option.label ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'
                                        }`}
                                        disabled={loading && range.label === option.label}
                                    >
                                        {option.label}
                                    </button>
                                ))}
                            </div>
                        </div>
                        <div className="flex items-center gap-3 flex-wrap">
                            <CardDropdown
                                cards={cards}
                                selected={selectedCard}
                                onChange={value => setSelectedCard(value)}
                                loading={loading}
                            />
                            {error && <p className="text-sm text-red-600 font-semibold">{error}</p>}
                        </div>
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <StatCard label="Views" value={totals.views} accent="bg-blue-500" />
                        <StatCard label="New views" value={totals.new_views} accent="bg-emerald-500" />
                        <StatCard label="Returning views" value={totals.returning_views} accent="bg-indigo-500" />
                        <StatCard label="Clicks" value={totals.clicks} accent="bg-orange-500" />
                        <StatCard label="QR scans" value={totals.qr_scans} accent="bg-purple-500" />
                        <StatCard label="NFC scans" value={totals.nfc_scans} accent="bg-amber-500" />
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6">
                    <div className="bg-white rounded-xl shadow p-4 md:p-6">
                        <div className="flex items-center justify-between mb-3">
                            <h2 className="text-lg font-bold text-gray-900">Top actions</h2>
                            <span className="text-sm text-gray-500">Clicks</span>
                        </div>
                        {data?.top_clicks?.length ? (
                            <ul className="divide-y divide-gray-200">
                                {data.top_clicks.map(item => (
                                    <li key={item.event_name} className="py-3 flex items-center justify-between">
                                        <span className="font-semibold text-gray-800 capitalize">{item.event_name || 'unknown'}</span>
                                        <span className="text-gray-700 font-bold">{item.count}</span>
                                    </li>
                                ))}
                            </ul>
                        ) : (
                            <p className="text-sm text-gray-600">No click data yet.</p>
                        )}
                    </div>

                    <div className="bg-white rounded-xl shadow p-4 md:p-6 overflow-hidden">
                        <div className="flex items-center justify-between mb-3">
                            <h2 className="text-lg font-bold text-gray-900">Daily activity</h2>
                            <span className="text-sm text-gray-500">Views & clicks</span>
                        </div>
                        {data?.timeseries?.length ? (
                            <div className="overflow-x-auto">
                                <table className="min-w-full text-left">
                                    <thead>
                                        <tr className="text-sm text-gray-500">
                                            <th className="py-2">Date</th>
                                            <th className="py-2">Views</th>
                                            <th className="py-2">Clicks</th>
                                        </tr>
                                    </thead>
                                    <tbody className="text-gray-800">
                                        {data.timeseries.map(point => (
                                            <tr key={point.date} className="border-t border-gray-100">
                                                <td className="py-2">{point.date}</td>
                                                <td className="py-2 font-semibold">{point.views}</td>
                                                <td className="py-2 font-semibold">{point.clicks}</td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        ) : (
                            <p className="text-sm text-gray-600">No activity for this range.</p>
                        )}
                    </div>
                </div>
            </div>

            <BottomNav />
            <NavMenu open={openMenu} closeMenu={() => setOpenMenu(false)} />
        </div>
    );
};

export default Analytics;
