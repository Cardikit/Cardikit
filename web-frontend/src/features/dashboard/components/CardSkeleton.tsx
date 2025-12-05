/**
 * CardSkeleton
 * ------------
 * Loading placeholder for a digital card preview within the dashboard.
 *
 * Responsibilities:
 * - Mimics the layout and vertical spacing of a real Card component while
 *   data is loading.
 * - Uses animated gray blocks (`animate-pulse`) to represent:
 *   - Banner / header text placeholders
 *   - Multiple item rows
 *   - Action or content blocks at the bottom
 * - Ensures consistent sizing with real cards (`h-[600px]`) so the carousel
 *   and surrounding layout do not shift during loading.
 *
 * UX notes:
 * - Placed inside the CardCarousel while cards are being fetched.
 * - Provides a smooth, modern skeleton-loading effect that feels responsive
 *   and familiar to users.
 *
 * @component
 * @since 0.0.2
 */
const CardSkeleton: React.FC = () => {

    return (
        <div className="p-10 flex flex-col items-center">
            <div className="bg-white rounded-xl shadow h-[600px] w-full md:w-3/4 lg:w-1/2 animate-pulse px-8 py-10 flex flex-col justify-between">
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
