const CardSkeleton: React.FC = () => {

    return (
        <div className="p-10">
            <div className="bg-white rounded-xl shadow h-[600px] w-full animate-pulse px-8 py-10 flex flex-col justify-between">
                <div className="space-y-6">
                    <div className="h-10 w-40 bg-gray-100 rounded" />
                    <div className="h-10 w-3/4 bg-gray-100 rounded" />
                    <div className="h-10 w-2/3 bg-gray-100 rounded" />
                    <div className="h-10 w-1/2 bg-gray-100 rounded" />
                </div>
                <div className="space-y-4">
                    <div className="h-12 w-full bg-gray-100 rounded" />
                    <div className="h-12 w-full bg-gray-100 rounded" />
                    <div className="h-12 w-full bg-gray-100 rounded" />
                </div>
            </div>
        </div>
    );
}

export default CardSkeleton;
