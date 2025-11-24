import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import AuthLayout from '@/features/auth/components/AuthLayout';
import Input from '@/features/auth/components/Input';
import { Checkbox } from '@/components/ui/checkbox';
import { IoIosMail, IoIosLock, IoMdContact } from 'react-icons/io';
import { Link } from 'react-router-dom';
import Button from '@/components/Button';
import { useForm, Controller } from 'react-hook-form';
import { yupResolver } from '@hookform/resolvers/yup';
import { registerSchema } from '@/features/auth/validationSchema';
import { useRegisterUser } from '@/features/auth/hooks/useRegisterUser';
import { useAuth } from '@/contexts/AuthContext';
/**
* Register Screen
* ---------------
*  This screen enables new users to create an account in the Cardikit app.
*  It includes:
*  - Inputs for name, email, password, confirm password
*  - Terms & Conditions checkbox (validated)
*  - Error handling for duplicate accounts or input issues
*  - Link to login for existing users
*
*  On success, triggers auth context refresh to reflect the new session.
*  UI follows a clean, mobile-first layout with friendly form UX.
*
*  @since 0.0.1
*/
const Register = () => {
    const { register, handleSubmit, control, formState: { errors }, } = useForm({
        resolver: yupResolver(registerSchema),
        defaultValues: {
            name: '',
            email: '',
            password: '',
            confirmPassword: '',
            acceptTerms: false
        }
    });
    const { register: registerUser, loading, error } = useRegisterUser();
    const { refresh } = useAuth();
    /**
    * Handles form submission for user registration.
    *
    * This function:
    * - Sends user input (name, email, password) to the register API.
    * - On success, refreshes the authentication context to reflect the new user session.
    * - On failure, logs the error to the console.
    *
    * @param {RegisterFormValues} payload - The form data submitted by the user.
    * @param {string} payload.name - The user's name.
    * @param {string} payload.email - The user's email address.
    * @param {string} payload.password - The user's password.
    * @param {string} payload.confirmPassword - The user's password confirmation.
    * @param {boolean} payload.terms - Whether the user has accepted the terms and conditions.
    *
    * @returns {Promise<void>}
    *
    * @since 0.0.1
    */
    const onSubmit = async (payload) => {
        try {
            await registerUser({
                name: payload.name,
                email: payload.email,
                password: payload.password
            });
            await refresh();
        }
        catch (err) {
            console.log(err);
        }
    };
    return (_jsxs(AuthLayout, { children: [error && _jsx("p", { className: "text-red-500 font-inter", children: error }), _jsx("h1", { className: "font-bold font-inter leading-snug tracking-tight text-2xl text-center text-gray-800", children: "Create an account" }), _jsxs("form", { className: "w-full flex flex-col gap-4 mt-6", onSubmit: handleSubmit(onSubmit), children: [_jsx(Input, { ...register('name'), startAdornment: _jsx(IoMdContact, { className: "text-primary-500" }), placeholder: "Enter your name", type: "text", error: errors?.name?.message }), _jsx(Input, { ...register('email'), startAdornment: _jsx(IoIosMail, { className: "text-primary-500" }), placeholder: "Enter your email", type: "email", error: errors?.email?.message }), _jsx(Input, { ...register('password'), startAdornment: _jsx(IoIosLock, { className: "text-primary-500" }), placeholder: "Enter your password", type: "password", error: errors?.password?.message }), _jsx(Input, { ...register('confirmPassword'), startAdornment: _jsx(IoIosLock, { className: "text-primary-500" }), placeholder: "Confirm your password", type: "password", error: errors?.confirmPassword?.message }), _jsxs("div", { className: "flex items-center gap-2", children: [_jsx(Controller, { name: "acceptTerms", control: control, defaultValue: false, render: ({ field }) => (_jsx(Checkbox, { className: "cursor-pointer bg-gray-200 data-[state=checked]:bg-primary-500 data-[state=checked]:border-primary-500", id: "accept-terms", checked: field.value, onCheckedChange: field.onChange })) }), _jsxs("p", { className: "font-inter text-gray-800", children: ["I agree to the ", _jsx("span", { className: "text-primary-500", children: "Terms & Conditions" }), " and ", _jsx("span", { className: "text-primary-500", children: "Privacy Policy" })] })] }), errors?.acceptTerms?.message && _jsx("p", { className: "text-red-500 text-sm", children: errors?.acceptTerms?.message }), _jsx(Button, { loading: loading, type: "submit", children: "Sign up" }), _jsxs("p", { className: "text-center font-inter text-gray-800", children: ["Already have an account? ", _jsx(Link, { className: "text-primary-500", to: "/login", children: "Sign in" })] })] })] }));
};
export default Register;
