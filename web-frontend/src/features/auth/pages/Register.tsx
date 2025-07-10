import AuthLayout from '@/features/auth/components/AuthLayout';
import Input from '@/features/auth/components/Input';
import { Checkbox } from '@/components/ui/checkbox';
import { IoIosMail, IoIosLock, IoMdContact } from 'react-icons/io'
import { Link } from 'react-router-dom';

import { useForm, Controller } from 'react-hook-form';
import { yupResolver } from '@hookform/resolvers/yup';
import { registerSchema } from '@/features/auth/validationSchema';

import { useRegisterUser } from '@/features/auth/hooks/useRegisterUser';
import { useAuth } from '@/contexts/AuthContext';

const Register: React.FC = () => {
    const { register, handleSubmit, control, formState: { errors }, } = useForm({ resolver: yupResolver(registerSchema) });
    const { register: registerUser, loading, error } = useRegisterUser();
    const { refresh } = useAuth();

    const onSubmit = async (data: any) => {
        try {
            await registerUser({
                name: data.name,
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
            <h1 className="font-bold font-inter leading-snug tracking-tight text-2xl text-center text-gray-800">Create an account</h1>
            <form
                className="w-full flex flex-col gap-4 mt-6"
                onSubmit={handleSubmit(onSubmit)}
            >
                <Input
                    {...register('name')}
                    startAdornment={<IoMdContact className="text-[#FA3C25]"/>}
                    placeholder="Enter your name"
                    type="text"
                    error={errors?.name?.message}
                />
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
                <Input
                    {...register('confirmPassword')}
                    startAdornment={<IoIosLock className="text-[#FA3C25]"/>}
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
                                className="cursor-pointer bg-gray-200 data-[state=checked]:bg-[#FA3C25] data-[state=checked]:border-[#FA3C25]"
                                id="accept-terms"
                                checked={field.value}
                                onCheckedChange={field.onChange}
                            />
                        )}
                    />
                    <p className="font-inter">I agree to the <span className="text-[#FA3C25]">Terms & Conditions</span> and <span className="text-[#FA3C25]">Privacy Policy</span></p>
                </div>
                {errors?.acceptTerms?.message && <p className="text-red-500 text-sm">{errors?.acceptTerms?.message}</p>}
                <button
                    className="cursor-pointer bg-[#FA3C25] border border-[#FA3C25] font-inter hover:bg-[#c92f1c] hover:-translate-y-1 transition-all ease-in-out shadow-md duration-200 text-[#FBFBFB] text-xl w-full py-3 rounded-lg flex items-center justify-center"
                    type="submit"
                    disabled={loading}
                >
                    {loading ? 'Loading...' : 'Sign Up'}
                </button>

                <p className="text-center font-inter">Already have an account? <Link className="text-[#FA3C25]" to="/login">Sign in</Link></p>
            </form>
        </AuthLayout>
    );
}

export default Register;
