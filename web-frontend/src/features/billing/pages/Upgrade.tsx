import { useState } from 'react';
import { Link } from 'react-router-dom';
import DesktopNav from '@/features/dashboard/components/DesktopNav';
import NavMenu from '@/features/dashboard/components/NavMenu';
import BottomNav from '@/features/dashboard/components/BottomNav';
import { MdOutlineMenu } from 'react-icons/md';
import { FaCheckCircle, FaLock } from 'react-icons/fa';

const plans = [
    {
        id: 'annual',
        label: 'Annually',
        note: 'Save 20%',
        price: '$84',
        subtext: '$7/month',
        billing: 'year',
    },
    {
        id: 'monthly',
        label: 'Monthly',
        note: '',
        price: '$9',
        subtext: 'Billed monthly',
        billing: 'month',
    },
];

const Upgrade: React.FC = () => {
    const [openMenu, setOpenMenu] = useState(false);
    const [selectedPlan, setSelectedPlan] = useState<'annual' | 'monthly'>('annual');

    const ctaLink = "/account";

    return (
        <div className="min-h-dvh bg-gray-100 pt-6">
            <DesktopNav />
            <div className="w-full h-full pb-24 px-4 lg:px-8 flex flex-col lg:w-3/4 lg:ml-auto max-w-6xl space-y-6">
                <header>
                    <MdOutlineMenu onClick={() => setOpenMenu(true)} className="text-3xl cursor-pointer lg:hidden" />
                    <div className="mt-4 flex flex-col justify-center items-center gap-6">
                        <div className="bg-primary-500 rounded-full px-2 py-1 flex items-center justify-center">
                            <p className="font-bold text-sm text-gray-100 font-inter">Start your 14-day free trial</p>
                        </div>
                        <h1 className="font-bold text-xl text-gray-900 text-center">Unlock your full potential, upgrade to Cardikit pro</h1>
                    </div>
                </header>

                <div className="bg-white rounded-xl shadow p-4 md:p-6 space-y-4">
                    <div className="flex flex-col gap-3">
                        <div>
                            <p className="text-sm text-gray-600">Choose your plan</p>
                            <h2 className="text-xl font-bold text-gray-900">Unlock Pro features</h2>
                        </div>
                        <div className="flex flex-col items-center gap-3 w-full">
                            {plans.map(plan => (
                                <button
                                    key={plan.id}
                                    onClick={() => setSelectedPlan(plan.id as 'annual' | 'monthly')}
                                    className={`cursor-pointer rounded-lg border px-4 py-2 text-left shadow-sm w-full ${
                                        selectedPlan === plan.id ? 'border-primary-500 bg-primary-50 text-primary-800' : 'border-gray-300 bg-white text-gray-800'
                                    }`}
                                >
                                    <div className="flex justify-between items-center">
                                        <div>
                                            {plan.note && <p className="text-xs font-bold text-emerald-600">{plan.note}</p>}
                                            <span className="font-semibold">{plan.label}</span>
                                            <div className="flex items-center gap-2">
                                                <div className="text-lg font-bold">{plan.price}</div>
                                                <div className="text-xs text-gray-600">{plan.subtext}</div>
                                            </div>
                                        </div>
                                        <div className={`size-6 rounded-full border flex items-center justify-center ${selectedPlan === plan.id ? 'border-primary-500' : 'border-gray-300'}`}>
                                            <div className={`size-3 rounded-full ${selectedPlan === plan.id ? 'bg-primary-500' : 'hidden'}`} />
                                        </div>
                                    </div>
                                </button>
                            ))}
                        </div>
                    </div>
                    <div className="w-full flex justify-center">
                        <span className="text-gray-600 text-sm">14-day free trial. Cancel anytime.</span>
                    </div>
                </div>

                <div className="bg-white rounded-xl shadow p-4 md:p-6">
                    <h3 className="text-lg font-bold text-gray-900 mb-4">Compare plans</h3>
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr className="text-left text-sm text-gray-600">
                                    <th className="py-2 pr-4 font-semibold">Feature</th>
                                    <th className="py-2 pr-4 font-semibold">Free</th>
                                    <th className="py-2 pr-4 font-semibold">Pro</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100 text-sm">
                                <tr>
                                    <td className="py-3 pr-4 font-semibold text-gray-900">Maximum number of cards</td>
                                    <td className="py-3 pr-4 text-gray-700">4</td>
                                    <td className="py-3 pr-4 text-primary-500 font-semibold">Unlimited</td>
                                </tr>
                                <tr>
                                    <td className="py-3 pr-4 font-semibold text-gray-900">Custom logo in QR code</td>
                                    <td className="py-3 pr-4 text-gray-500 items-center gap-2"><FaLock /></td>
                                    <td className="py-3 pr-4 text-primary-500 items-center gap-2"><FaCheckCircle /></td>
                                </tr>
                                <tr>
                                    <td className="py-3 pr-4 font-semibold text-gray-900">Analytics & insights</td>
                                    <td className="py-3 pr-4 text-gray-500"><FaLock /></td>
                                    <td className="py-3 pr-4 text-primary-500"><FaCheckCircle /></td>
                                </tr>
                                <tr>
                                    <td className="py-3 pr-4 font-semibold text-gray-900">Lead capture forms</td>
                                    <td className="py-3 pr-4 text-gray-500"><FaLock /></td>
                                    <td className="py-3 pr-4 text-primary-500"><FaCheckCircle /></td>
                                </tr>
                                <tr>
                                    <td className="py-3 pr-4 font-semibold text-gray-900">Premium themes</td>
                                    <td className="py-3 pr-4 text-gray-500"><FaLock /></td>
                                    <td className="py-3 pr-4 text-primary-500"><FaCheckCircle /></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="flex flex-col items-center gap-3 pb-24">
                    <p className="text-sm text-gray-700">Ready to get more from Cardikit?</p>
                </div>
            </div>

            <BottomNav />
            <NavMenu open={openMenu} closeMenu={() => setOpenMenu(false)} />
            <Link to={ctaLink} className="fixed bottom-24 right-1/2 translate-x-1/2 lg:right-43 lg:translate-x-0 z-3 bg-primary-500 hover:bg-primary-600 text-white font-bold w-11/12 lg:w-1/2 py-2 px-4 rounded flex justify-center items-center">
                <span>Start 14-day free trial</span>
            </Link>

        </div>
    );
};

export default Upgrade;
