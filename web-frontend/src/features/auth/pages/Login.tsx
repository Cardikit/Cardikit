import AuthLayout from '@/features/auth/components/AuthLayout';
import Input from '@/features/auth/components/Input';
import { IoIosMail, IoIosLock } from 'react-icons/io'
import { Link } from 'react-router-dom';
import Button from '@/components/Button';

import { useForm } from 'react-hook-form';
import { yupResolver } from '@hookform/resolvers/yup';
import { loginSchema } from '@/features/auth/validationSchema';

import { useLoginUser } from '@/features/auth/hooks/useLoginUser';
import { useAuth } from '@/contexts/AuthContext';

interface LoginFormValues {
    email: string;
    password: string;
}

/**
* Login Screen
* ------------
*  This screen allows existing users to authenticate into the Cardikit app.
*  It includes:
*  - Email and password inputs with validation
*  - Error messaging on invalid credentials
*  - Link to registration for new users
*
*  Integrates with the auth context to refresh the user session on success.
*  UI is optimized for mobile with an accessible, modern form layout.
*
*  @since 0.0.1
*/
const Login: React.FC = () => {
    const { register, handleSubmit, formState: { errors }, } = useForm<LoginFormValues>({ resolver: yupResolver(loginSchema) });
    const { login, loading, error } = useLoginUser();
    const { refresh } = useAuth();

    const onSubmit = async (payload: LoginFormValues) => {
        try {
            await login({
                email: payload.email,
                password: payload.password
            });
            await refresh();
        } catch (err) {
            console.log(err);
        }
    }

    return (
        <AuthLayout>
            {error && <p className="text-red-500 font-inter">{error}</p>}
            <h1 className="font-bold font-inter leading-snug tracking-tight text-2xl sm:text-3xl text-center text-gray-800">Welcome back!</h1>
            <form
                className="w-full flex flex-col gap-4 mt-6"
                onSubmit={handleSubmit(onSubmit)}
            >
                <Input
                    {...register('email')}
                    startAdornment={<IoIosMail className="text-primary-500"/>}
                    placeholder="Enter your email"
                    type="email"
                    error={errors?.email?.message}
                />
                <Input
                    {...register('password')}
                    startAdornment={<IoIosLock className="text-primary-500"/>}
                    placeholder="Enter your password"
                    type="password"
                    error={errors?.password?.message}
                />
                <Button loading={loading} type="submit">Sign in</Button>

                <p className="text-center font-inter text-gray-800 text-sm sm:text-base">Don't have an account? <Link className="text-primary-500" to="/register">Sign up</Link></p>
            </form>
        </AuthLayout>
    );
}

export default Login;
