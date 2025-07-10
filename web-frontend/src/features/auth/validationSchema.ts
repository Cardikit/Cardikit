import * as yup from 'yup';

export const registerSchema = yup.object({
    name: yup.string().min(2, 'Name must be at least 2 characters long').max(50, 'Name must be less than 50 characters long').required('Name is required'),
    email: yup.string().email('Invalid email').required('Email is required'),
    password: yup.string().min(6, 'Password must be at least 6 characters long').max(255, 'Password must be less than 255 characters long').required('Password is required'),
    confirmPassword: yup.string().oneOf([yup.ref('password')], 'Passwords must match').required('Confirm Password is required'),
    acceptTerms: yup.bool().oneOf([true], 'Terms must be accepted'),
});

export const loginSchema = yup.object({
    email: yup.string().email('Invalid email').required('Email is required'),
    password: yup.string().max(255, 'Password must be less than 255 characters long').required('Password is required'),
});
