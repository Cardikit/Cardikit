import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import AuthLayout from '@/features/auth/components/AuthLayout';
import Input from '@/features/auth/components/Input';
import { IoIosMail, IoIosLock } from 'react-icons/io';
import { Link } from 'react-router-dom';
import Button from '@/components/Button';
import { useForm } from 'react-hook-form';
import { yupResolver } from '@hookform/resolvers/yup';
import { loginSchema } from '@/features/auth/validationSchema';
import { useLoginUser } from '@/features/auth/hooks/useLoginUser';
import { useAuth } from '@/contexts/AuthContext';
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
const Login = () => {
    const { register, handleSubmit, formState: { errors }, } = useForm({ resolver: yupResolver(loginSchema) });
    const { login, loading, error } = useLoginUser();
    const { refresh } = useAuth();
    const onSubmit = async (payload) => {
        try {
            await login({
                email: payload.email,
                password: payload.password
            });
            await refresh();
        }
        catch (err) {
            console.log(err);
        }
    };
    return (_jsxs(AuthLayout, { children: [error && _jsx("p", { className: "text-red-500 font-inter", children: error }), _jsx("h1", { className: "font-bold font-inter leading-snug tracking-tight text-2xl text-center text-gray-800", children: "Welcome back!" }), _jsxs("form", { className: "w-full flex flex-col gap-4 mt-6", onSubmit: handleSubmit(onSubmit), children: [_jsx(Input, { ...register('email'), startAdornment: _jsx(IoIosMail, { className: "text-primary-500" }), placeholder: "Enter your email", type: "email", error: errors?.email?.message }), _jsx(Input, { ...register('password'), startAdornment: _jsx(IoIosLock, { className: "text-primary-500" }), placeholder: "Enter your password", type: "password", error: errors?.password?.message }), _jsx(Button, { loading: loading, type: "submit", children: "Sign in" }), _jsxs("p", { className: "text-center font-inter text-gray-800", children: ["Don't have an account? ", _jsx(Link, { className: "text-primary-500", to: "/register", children: "Sign up" })] })] })] }));
};
export default Login;
