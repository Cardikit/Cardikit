import AuthLayout from '@/features/auth/components/AuthLayout';
import Input from '@/features/auth/components/Input';
import { IoIosMail, IoIosLock } from 'react-icons/io'
import { Link } from 'react-router-dom';

import { useForm } from 'react-hook-form';
import { yupResolver } from '@hookform/resolvers/yup';
import { loginSchema } from '@/features/auth/validationSchema';

import { useLoginUser } from '@/features/auth/hooks/useLoginUser';
import { useAuth } from '@/contexts/AuthContext';

const Login: React.FC = () => {
    const { register, handleSubmit, formState: { errors }, } = useForm({ resolver: yupResolver(loginSchema) });
    const { login, loading, error } = useLoginUser();
    const { refresh } = useAuth();

    const onSubmit = async (data: any) => {
        try {
            await login({
                email: data.email,
                password: data.password
            });
            await refresh();
        } catch (err) {
            console.log(err);
        }
    }

    return (
        <AuthLayout>
            {error && <p className="text-red-500 font-inter">{error}</p>}
            <h1 className="font-bold font-inter leading-snug tracking-tight text-2xl text-center text-gray-800">Welcome back!</h1>
            <form
                className="w-full flex flex-col gap-4 mt-6"
                onSubmit={handleSubmit(onSubmit)}
            >
                <Input
                    {...register('email')}
                    startAdornment={<IoIosMail className="text-[#FA3C25]"/>}
                    placeholder="Enter your email"
                    type="email"
                    error={errors?.email?.message}
                />
                <Input
                    {...register('password')}
                    startAdornment={<IoIosLock className="text-[#FA3C25]"/>}
                    placeholder="Enter your password"
                    type="password"
                    error={errors?.password?.message}
                />
                <button
                    className="cursor-pointer bg-[#FA3C25] border border-[#FA3C25] font-inter hover:bg-[#c92f1c] hover:-translate-y-1 transition-all ease-in-out shadow-md duration-200 text-[#FBFBFB] text-xl w-full py-3 rounded-lg flex items-center justify-center"
                    type="submit"
                    disabled={loading}
                >
                    {loading ? 'Loading...' : 'Log in'}
                </button>

                <p className="text-center font-inter">Don't have an account? <Link className="text-[#FA3C25]" to="/register">Sign up</Link></p>
            </form>
        </AuthLayout>
    );
}

export default Login;
