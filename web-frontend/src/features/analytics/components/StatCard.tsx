interface StatCardProps {
    label: string;
    value: number;
    accent: string;
}

const StatCard: React.FC<StatCardProps> = ({ label, value, accent }) => (
    <div className="bg-gray-50 border border-gray-200 rounded-xl p-4 shadow-sm flex items-center justify-between">
        <div>
            <p className="text-sm text-gray-600">{label}</p>
            <p className="text-2xl font-bold text-gray-900">{value}</p>
        </div>
        <div className={`w-3 h-12 rounded-full ${accent}`} />
    </div>
);

export default StatCard;
