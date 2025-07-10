import Header from '@/assets/header.webp';
import Back from '@/components/Back';
import Input from '@/features/auth/components/Input';
import { Checkbox } from '@/components/ui/checkbox';
import { IoIosMail, IoIosLock, IoMdContact } from 'react-icons/io'
import { Link } from 'react-router-dom';

const Register: React.FC = () => {
    return (
        <div className="w-full h-dvh overflow-x-hidden bg-[#E3E3E3] flex flex-col">
            <Back />
            <img className="w-3/4 mx-auto" src={Header} alt="Header Image" />
            <div className="w-full bg-[#FBFBFB] rounded-t-4xl flex flex-col items-center flex-grow px-6 py-8 mt-8">
                <h1 className="font-bold font-inter leading-snug tracking-tight text-2xl text-center text-gray-800">Create an account</h1>
                <form
                    className="w-full flex flex-col gap-4 mt-6"
                    onSubmit={(e) => {
                        e.preventDefault();
                    }}
                >
                    <Input
                        startAdornment={<IoMdContact className="text-[#FA3C25]"/>}
                        placeholder="Enter your name"
                    />
                    <Input
                        startAdornment={<IoIosMail className="text-[#FA3C25]"/>}
                        placeholder="Enter your email"
                    />
                    <Input
                        startAdornment={<IoIosLock className="text-[#FA3C25]"/>}
                        placeholder="Enter your password"
                        type="password"
                    />
                    <Input
                        startAdornment={<IoIosLock className="text-[#FA3C25]"/>}
                        placeholder="Confirm your password"
                        type="password"
                    />
                    <div className="flex items-center gap-2">
                        <Checkbox className="cursor-pointer bg-gray-200 data-[state=checked]:bg-[#FA3C25] data-[state=checked]:border-[#FA3C25]" id="accept-terms" />
                        <p className="font-inter">I agree to the <span className="text-[#FA3C25]">Terms & Conditions</span> and <span className="text-[#FA3C25]">Privacy Policy</span></p>
                    </div>
                    <button className="cursor-pointer bg-[#FA3C25] border border-[#FA3C25] font-inter hover:bg-[#c92f1c] hover:-translate-y-1 transition-all ease-in-out shadow-md duration-200 text-[#FBFBFB] text-xl w-full py-3 rounded-lg flex items-center justify-center">Sign Up</button>

                    <p className="text-center font-inter">Already have an account? <Link className="text-[#FA3C25]" to="/login">Sign in</Link></p>
                </form>
            </div>
        </div>
    );
}

export default Register;

