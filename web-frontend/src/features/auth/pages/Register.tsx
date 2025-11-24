import AuthLayout from '@/features/auth/components/AuthLayout';
import Input from '@/features/auth/components/Input';
import { Checkbox } from '@/components/ui/checkbox';
import { IoIosMail, IoIosLock, IoMdContact } from 'react-icons/io'
import { Link } from 'react-router-dom';
import Button from '@/components/Button';

import { useForm, Controller, type SubmitHandler } from 'react-hook-form';
import { yupResolver } from '@hookform/resolvers/yup';
import { registerSchema } from '@/features/auth/validationSchema';

import { useRegisterUser } from '@/features/auth/hooks/useRegisterUser';
import { useAuth } from '@/contexts/AuthContext';

interface RegisterFormValues {
    name: string;
    email: string;
    password: string;
    confirmPassword: string;
    acceptTerms: boolean;
}

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
const Register: React.FC = () => {
    const { register, handleSubmit, control, formState: { errors }, } = useForm<RegisterFormValues>({
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
    const onSubmit: SubmitHandler<RegisterFormValues> = async (payload) => {
        try {
            await registerUser({
                name: payload.name,
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
            <h1 className="font-bold font-inter leading-snug tracking-tight text-2xl sm:text-3xl text-center text-gray-800">Create an account</h1>
            <form
                className="w-full flex flex-col gap-4 mt-6"
                onSubmit={handleSubmit(onSubmit)}
            >
                <Input
                    {...register('name')}
                    startAdornment={<IoMdContact className="text-primary-500"/>}
                    placeholder="Enter your name"
                    type="text"
                    error={errors?.name?.message}
                />
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
                <Input
                    {...register('confirmPassword')}
                    startAdornment={<IoIosLock className="text-primary-500"/>}
                    placeholder="Confirm your password"
                    type="password"
                    error={errors?.confirmPassword?.message}
                />
                <div className="flex items-center gap-2">
                    <Controller
                        name="acceptTerms"
                        control={control}
                        defaultValue={false}
                        render={({ field }) => (
                            <Checkbox
                                className="cursor-pointer bg-gray-200 data-[state=checked]:bg-primary-500 data-[state=checked]:border-primary-500"
                                id="accept-terms"
                                checked={field.value}
                                onCheckedChange={field.onChange}
                            />
                        )}
                    />
                    <p className="font-inter text-gray-800 text-sm sm:text-base">I agree to the <span className="text-primary-500">Terms & Conditions</span> and <span className="text-primary-500">Privacy Policy</span></p>
                </div>
                {errors?.acceptTerms?.message && <p className="text-red-500 text-sm">{errors?.acceptTerms?.message}</p>}
                <Button loading={loading} type="submit">Sign up</Button>

                <p className="text-center font-inter text-gray-800 text-sm sm:text-base">Already have an account? <Link className="text-primary-500" to="/login">Sign in</Link></p>
            </form>
        </AuthLayout>
    );
}

export default Register;
